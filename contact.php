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

/* Build mail — sujet clair + version HTML structurée + version texte */
$langNames = ['fr' => 'Français', 'en' => 'Anglais', 'it' => 'Italien', 'de' => 'Allemand', 'hr' => 'Croate'];
$langLabel = $langNames[$lang] ?? $lang;
$when      = date('d/m/Y à H:i');
$subject   = 'Question site — ' . $name . ($apartment ? ' — ' . $apartment : '') . ($dates ? ' — ' . $dates : '');

$text  = "NOUVELLE QUESTION DEPUIS LE SITE\n";
$text .= str_repeat('=', 44) . "\n\n";
$text .= "De          : $name\n";
$text .= "Email       : $email\n";
$text .= "Appartement : " . ($apartment ?: 'non précisé') . "\n";
$text .= "Dates       : " . ($dates ?: 'non précisées') . "\n";
$text .= "Langue      : $langLabel\n";
$text .= "Reçu le     : $when\n\n";
$text .= "MESSAGE\n" . str_repeat('-', 44) . "\n" . $message . "\n\n";
$text .= str_repeat('-', 44) . "\n";
$text .= "Pour répondre : cliquez simplement sur « Répondre », votre réponse partira à $email.\n";

$h = fn(string $v): string => htmlspecialchars($v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$row = fn(string $label, string $value): string =>
    '<tr><td style="padding:10px 0;border-bottom:1px solid #eee;color:#999;font-size:12px;letter-spacing:1px;text-transform:uppercase;width:130px;vertical-align:top">' . $label . '</td>'
  . '<td style="padding:10px 0;border-bottom:1px solid #eee;color:#1a1a1a;font-size:15px">' . $value . '</td></tr>';

$html  = '<!DOCTYPE html><html><body style="margin:0;padding:0;background:#f4f2ee;font-family:Arial,Helvetica,sans-serif">';
$html .= '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f2ee;padding:24px 12px"><tr><td align="center">';
$html .= '<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:10px;overflow:hidden">';
$html .= '<tr><td style="background:#111111;padding:26px 32px"><div style="color:#C9A96E;font-size:11px;letter-spacing:3px;text-transform:uppercase">Les Terrasses de Lumbarda</div>';
$html .= '<div style="color:#ffffff;font-size:22px;font-family:Georgia,serif;font-style:italic;margin-top:6px">Nouvelle question d’un visiteur</div></td></tr>';
$html .= '<tr><td style="padding:24px 32px 8px"><table role="presentation" width="100%" cellpadding="0" cellspacing="0">';
$html .= $row('De', '<strong>' . $h($name) . '</strong>');
$html .= $row('Email', '<a href="mailto:' . $h($email) . '" style="color:#b8944f">' . $h($email) . '</a>');
$html .= $row('Appartement', $apartment ? $h($apartment) : '<span style="color:#aaa">non précisé</span>');
$html .= $row('Dates', $dates ? $h($dates) : '<span style="color:#aaa">non précisées</span>');
$html .= $row('Langue', $h($langLabel));
$html .= $row('Reçu le', $h($when));
$html .= '</table></td></tr>';
$html .= '<tr><td style="padding:16px 32px 8px"><div style="color:#999;font-size:12px;letter-spacing:1px;text-transform:uppercase;margin-bottom:10px">Message</div>';
$html .= '<div style="background:#FAF9F7;border-left:3px solid #C9A96E;padding:16px 18px;color:#1a1a1a;font-size:15px;line-height:1.7">' . nl2br($h($message)) . '</div></td></tr>';
$html .= '<tr><td style="padding:22px 32px 30px" align="center"><a href="mailto:' . $h($email) . '?subject=' . rawurlencode('Re: votre demande — Les Terrasses de Lumbarda') . '" style="display:inline-block;background:#C9A96E;color:#ffffff;text-decoration:none;padding:13px 30px;border-radius:40px;font-size:13px;letter-spacing:1px;text-transform:uppercase">Répondre à ' . $h($name) . '</a>';
$html .= '<div style="color:#aaa;font-size:12px;margin-top:14px">Ou cliquez simplement sur « Répondre » : la réponse part directement au visiteur.</div></td></tr>';
$html .= '<tr><td style="background:#FAF9F7;padding:14px 32px;color:#aaa;font-size:11px;text-align:center">Message envoyé depuis le formulaire de contact de lesterrasses-lumbarda.com</td></tr>';
$html .= '</table></td></tr></table></body></html>';

$boundary   = 'lt_' . bin2hex(random_bytes(12));
$encSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
$encName    = '=?UTF-8?B?' . base64_encode($name) . '?=';
$headers  = "From: " . SITE_NAME . " <" . FROM_EMAIL . ">\r\n";
$headers .= "Reply-To: " . $encName . " <" . $email . ">\r\n"; // $email validé, sans CR/LF
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: multipart/alternative; boundary=\"$boundary\"\r\n";
$headers .= "X-Mailer: PHP/" . PHP_VERSION . "\r\n";

$body  = "--$boundary\r\nContent-Type: text/plain; charset=UTF-8\r\nContent-Transfer-Encoding: base64\r\n\r\n" . chunk_split(base64_encode($text)) . "\r\n";
$body .= "--$boundary\r\nContent-Type: text/html; charset=UTF-8\r\nContent-Transfer-Encoding: base64\r\n\r\n" . chunk_split(base64_encode($html)) . "\r\n";
$body .= "--$boundary--\r\n";

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
