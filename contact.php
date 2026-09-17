<?php
/**
 * Les Terrasses de Lumbarda — formulaire de contact
 * Hébergement OVH mutualisé (PHP >= 7.4). Aucun service tiers.
 *
 * Protections : honeypot, délai minimum, limite de débit par IP,
 * validation stricte, en-têtes mail nettoyés (anti header-injection).
 */

declare(strict_types=1);

/* ---------- CONFIGURATION ---------- */
const TO_EMAIL     = 'lesterrasses.lumbarda@gmail.com';     // destinataire des messages
const FROM_EMAIL   = 'no-reply@lesterrasses-lumbarda.com';  // expéditeur technique (doit être sur le domaine OVH)
const SITE_NAME    = 'Les Terrasses de Lumbarda';
const MIN_SECONDS  = 3;      // temps minimum entre affichage et envoi (anti-bot)
const RATE_LIMIT   = 5;      // envois max par IP...
const RATE_WINDOW  = 3600;   // ...par fenêtre (secondes)
/* ----------------------------------- */

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store');

function reply(bool $ok, string $error = ''): void {
    http_response_code($ok ? 200 : 400);
    echo json_encode(['ok' => $ok, 'error' => $error], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    reply(false, 'method');
}

/* Same-origin check (Origin/Referer must be this site) */
$host = $_SERVER['HTTP_HOST'] ?? '';
$origin = $_SERVER['HTTP_ORIGIN'] ?? $_SERVER['HTTP_REFERER'] ?? '';
if ($origin !== '' && $host !== '' && stripos($origin, '://' . $host) === false) {
    reply(false, 'origin');
}

/* Honeypot: real users never fill this */
if (!empty($_POST['website'])) {
    reply(true); // pretend success to bots
}

/* Minimum fill time */
$ts = (int)($_POST['ts'] ?? 0);
if ($ts <= 0 || (time() * 1000 - $ts) < MIN_SECONDS * 1000) {
    reply(false, 'fast');
}

/* Rate limiting per IP (file-based, no DB) */
$ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
$rateDir = sys_get_temp_dir() . '/lt_contact_rate';
if (!is_dir($rateDir)) { @mkdir($rateDir, 0700, true); }
$rateFile = $rateDir . '/' . hash('sha256', $ip);
$hits = [];
if (is_file($rateFile)) {
    $hits = array_filter(array_map('intval', file($rateFile, FILE_IGNORE_NEW_LINES)), fn($t) => $t > time() - RATE_WINDOW);
}
if (count($hits) >= RATE_LIMIT) {
    http_response_code(429);
    reply(false, 'rate');
}

/* Validation */
$clean = fn(string $v, int $max): string => mb_substr(trim(preg_replace('/[\r\n\t]+/', ' ', $v)), 0, $max);

$name      = $clean((string)($_POST['name'] ?? ''), 100);
$email     = $clean((string)($_POST['email'] ?? ''), 150);
$apartment = $clean((string)($_POST['apartment'] ?? ''), 40);
$dates     = $clean((string)($_POST['dates'] ?? ''), 80);
$lang      = preg_match('/^[a-z]{2}$/', (string)($_POST['lang'] ?? '')) ? $_POST['lang'] : 'fr';
$message   = mb_substr(trim((string)($_POST['message'] ?? '')), 0, 3000);

if ($name === '' || mb_strlen($name) < 2)                     reply(false, 'name');
if (!filter_var($email, FILTER_VALIDATE_EMAIL))               reply(false, 'email');
if ($message === '' || mb_strlen($message) < 10)              reply(false, 'message');
if (preg_match('/https?:\/\/\S+.*https?:\/\/\S+.*https?:\/\//is', $message)) reply(false, 'spam');

/* Record hit */
$hits[] = time();
@file_put_contents($rateFile, implode("\n", $hits), LOCK_EX);

/* Build mail */
$subject = '[' . SITE_NAME . '] Nouveau message' . ($apartment ? ' — ' . $apartment : '') . ' — ' . $name;
$body  = "Nouveau message depuis le site " . SITE_NAME . "\n";
$body .= str_repeat('-', 50) . "\n";
$body .= "Nom         : $name\n";
$body .= "Email       : $email\n";
$body .= "Appartement : " . ($apartment ?: '—') . "\n";
$body .= "Dates       : " . ($dates ?: '—') . "\n";
$body .= "Langue      : $lang\n";
$body .= "IP          : $ip\n";
$body .= "Date        : " . date('d/m/Y H:i') . "\n";
$body .= str_repeat('-', 50) . "\n\n";
$body .= $message . "\n";

$encSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
$headers  = "From: " . SITE_NAME . " <" . FROM_EMAIL . ">\r\n";
$headers .= "Reply-To: " . $name . " <" . $email . ">\r\n"; // $name/$email déjà nettoyés (pas de CR/LF)
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "Content-Transfer-Encoding: 8bit\r\n";
$headers .= "X-Mailer: PHP/" . PHP_VERSION . "\r\n";

$sent = @mail(TO_EMAIL, $encSubject, $body, $headers, '-f' . FROM_EMAIL);

if (!$sent) {
    http_response_code(500);
    reply(false, 'mail');
}

/* Accusé de réception au visiteur */
$ack = [
    'fr' => ["Votre message a bien été reçu", "Bonjour $name,\n\nNous avons bien reçu votre message et vous répondrons dans les plus brefs délais (sous 24h en général).\n\nÀ très bientôt à Lumbarda,\nLes Terrasses de Lumbarda\nhttps://lesterrasses-lumbarda.com"],
    'en' => ["We have received your message", "Hello $name,\n\nThank you for your message. We will get back to you shortly (usually within 24 hours).\n\nSee you soon in Lumbarda,\nLes Terrasses de Lumbarda\nhttps://lesterrasses-lumbarda.com"],
    'it' => ["Abbiamo ricevuto il suo messaggio", "Buongiorno $name,\n\nAbbiamo ricevuto il suo messaggio e le risponderemo al più presto (di solito entro 24 ore).\n\nA presto a Lumbarda,\nLes Terrasses de Lumbarda\nhttps://lesterrasses-lumbarda.com"],
    'de' => ["Wir haben Ihre Nachricht erhalten", "Guten Tag $name,\n\nvielen Dank für Ihre Nachricht. Wir melden uns in Kürze (in der Regel innerhalb von 24 Stunden).\n\nBis bald in Lumbarda,\nLes Terrasses de Lumbarda\nhttps://lesterrasses-lumbarda.com"],
    'hr' => ["Primili smo vašu poruku", "Poštovani $name,\n\nhvala na poruci. Odgovorit ćemo vam u najkraćem roku (obično unutar 24 sata).\n\nVidimo se u Lumbardi,\nLes Terrasses de Lumbarda\nhttps://lesterrasses-lumbarda.com"],
];
[$ackSubject, $ackBody] = $ack[$lang] ?? $ack['fr'];
$ackHeaders  = "From: " . SITE_NAME . " <" . FROM_EMAIL . ">\r\n";
$ackHeaders .= "Reply-To: " . TO_EMAIL . "\r\n";
$ackHeaders .= "MIME-Version: 1.0\r\nContent-Type: text/plain; charset=UTF-8\r\nContent-Transfer-Encoding: 8bit\r\n";
@mail($email, '=?UTF-8?B?' . base64_encode($ackSubject) . '?=', $ackBody, $ackHeaders, '-f' . FROM_EMAIL);

reply(true);
