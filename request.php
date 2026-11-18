<?php
/**
 * Les Terrasses de Lumbarda — demande de réservation (mode manuel, avant Smoobu)
 *
 * Reçoit : appartement, dates, voyageurs, nom, email, téléphone, message, langue.
 * Envoie : 1) un email structuré au propriétaire, 2) un accusé de réception au client.
 * Protections : honeypot, délai minimum, limite par IP, validation stricte,
 * en-têtes nettoyés, refus des dates passées ou incohérentes.
 */

declare(strict_types=1);

const TO_EMAIL    = 'lesterrasses.lumbarda@gmail.com';
const FROM_EMAIL  = 'no-reply@lesterrasses-lumbarda.com';
const SITE_NAME   = 'Les Terrasses de Lumbarda';
const HOST_NAME   = 'Thierry';
const HOST_PHONE  = '+33 6 84 75 68 51';
const MIN_SECONDS = 3;
const RATE_LIMIT  = 5;
const RATE_WINDOW = 3600;
const MAX_GUESTS  = 2;

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store');

function reply(bool $ok, string $error = ''): void {
    http_response_code($ok ? 200 : 400);
    echo json_encode(['ok' => $ok, 'error' => $error], JSON_UNESCAPED_UNICODE);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); reply(false, 'method'); }

$host = $_SERVER['HTTP_HOST'] ?? '';
$origin = $_SERVER['HTTP_ORIGIN'] ?? $_SERVER['HTTP_REFERER'] ?? '';
if ($origin !== '' && $host !== '' && stripos($origin, '://' . $host) === false) { reply(false, 'origin'); }
if (!empty($_POST['website'])) { reply(true); }
$ts = (int)($_POST['ts'] ?? 0);
if ($ts <= 0 || (time() * 1000 - $ts) < MIN_SECONDS * 1000) { reply(false, 'fast'); }

$ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
$rateDir = sys_get_temp_dir() . '/lt_request_rate';
if (!is_dir($rateDir)) { @mkdir($rateDir, 0700, true); }
$rateFile = $rateDir . '/' . hash('sha256', $ip);
$hits = [];
if (is_file($rateFile)) {
    $hits = array_filter(array_map('intval', file($rateFile, FILE_IGNORE_NEW_LINES)), fn($t) => $t > time() - RATE_WINDOW);
}
if (count($hits) >= RATE_LIMIT) { http_response_code(429); reply(false, 'rate'); }

$clean = fn(string $v, int $max): string => mb_substr(trim(preg_replace('/[\r\n\t]+/', ' ', $v)), 0, $max);
$name      = $clean((string)($_POST['name'] ?? ''), 100);
$email     = $clean((string)($_POST['email'] ?? ''), 150);
$phone     = $clean((string)($_POST['phone'] ?? ''), 40);
$apartment = $clean((string)($_POST['apartment'] ?? ''), 40);
$checkin   = $clean((string)($_POST['checkin'] ?? ''), 10);
$checkout  = $clean((string)($_POST['checkout'] ?? ''), 10);
$guests    = max(1, min(MAX_GUESTS, (int)($_POST['guests'] ?? 2)));
$lang      = preg_match('/^[a-z]{2}$/', (string)($_POST['lang'] ?? '')) ? $_POST['lang'] : 'fr';
$message   = mb_substr(trim((string)($_POST['message'] ?? '')), 0, 1500);

if ($name === '' || mb_strlen($name) < 2)          reply(false, 'name');
if (!filter_var($email, FILTER_VALIDATE_EMAIL))    reply(false, 'email');
$dIn  = DateTime::createFromFormat('Y-m-d', $checkin);
$dOut = DateTime::createFromFormat('Y-m-d', $checkout);
$today = new DateTime('today');
if (!$dIn || !$dOut || $dIn->format('Y-m-d') !== $checkin || $dOut->format('Y-m-d') !== $checkout || $dOut <= $dIn || $dIn < $today) reply(false, 'dates');
$nights = (int)$dIn->diff($dOut)->days;
if ($nights > 60) reply(false, 'dates');

$hits[] = time();
@file_put_contents($rateFile, implode("\n", $hits), LOCK_EX);

$fr = fn(DateTime $d): string => $d->format('d/m/Y');
$langNames = ['fr' => 'Français', 'en' => 'Anglais', 'it' => 'Italien', 'de' => 'Allemand', 'hr' => 'Croate'];
$langLabel = $langNames[$lang] ?? $lang;
$when = date('d/m/Y à H:i');
$h = fn(string $v): string => htmlspecialchars($v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

/* ---------- Email propriétaire ---------- */
$subject = "Demande de réservation — $apartment — " . $fr($dIn) . ' → ' . $fr($dOut) . " ($nights nuits) — $name";
$text  = "NOUVELLE DEMANDE DE RÉSERVATION\n" . str_repeat('=', 44) . "\n\n";
$text .= "Appartement : $apartment\nArrivée     : " . $fr($dIn) . "\nDépart      : " . $fr($dOut) . "\nNuits       : $nights\nVoyageurs   : $guests\n\n";
$text .= "Nom         : $name\nEmail       : $email\nTéléphone   : " . ($phone ?: '—') . "\nLangue      : $langLabel\nReçu le     : $when\n\n";
$text .= "MESSAGE\n" . str_repeat('-', 44) . "\n" . ($message ?: '—') . "\n\n";
$text .= str_repeat('-', 44) . "\nÀ FAIRE : vérifier le calendrier Booking/Airbnb, puis répondre au client (bouton Répondre) avec le tarif et le lien de paiement. Pensez à bloquer les dates sur Booking et Airbnb une fois l'acompte reçu.\n";
$row = fn(string $l, string $v): string => '<tr><td style="padding:9px 0;border-bottom:1px solid #eee;color:#999;font-size:12px;letter-spacing:1px;text-transform:uppercase;width:130px;vertical-align:top">' . $l . '</td><td style="padding:9px 0;border-bottom:1px solid #eee;color:#1a1a1a;font-size:15px">' . $v . '</td></tr>';
$html  = '<!DOCTYPE html><html><body style="margin:0;padding:0;background:#f4f2ee;font-family:Arial,Helvetica,sans-serif"><table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="padding:24px 12px"><tr><td align="center"><table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#fff;border-radius:10px;overflow:hidden">';
$html .= '<tr><td style="background:#111;padding:26px 32px"><div style="color:#C9A96E;font-size:11px;letter-spacing:3px;text-transform:uppercase">' . SITE_NAME . '</div><div style="color:#fff;font-size:22px;font-family:Georgia,serif;font-style:italic;margin-top:6px">Nouvelle demande de réservation</div></td></tr>';
$html .= '<tr><td style="padding:22px 32px 6px"><div style="background:#FAF9F7;border-left:3px solid #C9A96E;padding:14px 18px;font-size:17px;color:#1a1a1a"><strong>' . $h($apartment) . '</strong><br>' . $fr($dIn) . ' → ' . $fr($dOut) . ' · ' . $nights . ' nuit' . ($nights > 1 ? 's' : '') . ' · ' . $guests . ' voyageur' . ($guests > 1 ? 's' : '') . '</div></td></tr>';
$html .= '<tr><td style="padding:10px 32px 4px"><table role="presentation" width="100%" cellpadding="0" cellspacing="0">' . $row('Nom', '<strong>' . $h($name) . '</strong>') . $row('Email', '<a href="mailto:' . $h($email) . '" style="color:#b8944f">' . $h($email) . '</a>') . $row('Téléphone', $phone ? '<a href="https://wa.me/' . preg_replace('/\D/', '', $phone) . '" style="color:#b8944f">' . $h($phone) . '</a>' : '<span style="color:#aaa">non précisé</span>') . $row('Langue', $h($langLabel)) . $row('Reçu le', $h($when)) . '</table></td></tr>';
if ($message !== '') $html .= '<tr><td style="padding:14px 32px 4px"><div style="color:#999;font-size:12px;letter-spacing:1px;text-transform:uppercase;margin-bottom:8px">Message</div><div style="background:#FAF9F7;padding:14px 18px;color:#1a1a1a;font-size:15px;line-height:1.7">' . nl2br($h($message)) . '</div></td></tr>';
$html .= '<tr><td style="padding:20px 32px 10px"><div style="background:#fff8ea;border:1px solid #f0dcb0;border-radius:8px;padding:14px 18px;font-size:14px;color:#5a4a2a;line-height:1.7"><strong>À faire :</strong><br>1. Vérifier le calendrier Booking / Airbnb pour ces dates.<br>2. Répondre au client avec le tarif (−10 % par rapport aux plateformes, ménage offert) et le lien de paiement de l’acompte.<br>3. Une fois l’acompte reçu, bloquer les dates sur Booking et Airbnb.</div></td></tr>';
$html .= '<tr><td style="padding:14px 32px 30px" align="center"><a href="mailto:' . $h($email) . '?subject=' . rawurlencode('Votre réservation aux Terrasses de Lumbarda — ' . $fr($dIn) . ' au ' . $fr($dOut)) . '" style="display:inline-block;background:#C9A96E;color:#fff;text-decoration:none;padding:13px 30px;border-radius:40px;font-size:13px;letter-spacing:1px;text-transform:uppercase">Répondre à ' . $h($name) . '</a></td></tr>';
$html .= '<tr><td style="background:#FAF9F7;padding:14px 32px;color:#aaa;font-size:11px;text-align:center">Demande envoyée depuis lesterrasses-lumbarda.com</td></tr></table></td></tr></table></body></html>';

$boundary = 'lt_' . bin2hex(random_bytes(12));
$enc = fn(string $s): string => '=?UTF-8?B?' . base64_encode($s) . '?=';
$headers  = "From: " . SITE_NAME . " <" . FROM_EMAIL . ">\r\nReply-To: " . $enc($name) . " <" . $email . ">\r\nMIME-Version: 1.0\r\nContent-Type: multipart/alternative; boundary=\"$boundary\"\r\n";
$body  = "--$boundary\r\nContent-Type: text/plain; charset=UTF-8\r\nContent-Transfer-Encoding: base64\r\n\r\n" . chunk_split(base64_encode($text)) . "\r\n";
$body .= "--$boundary\r\nContent-Type: text/html; charset=UTF-8\r\nContent-Transfer-Encoding: base64\r\n\r\n" . chunk_split(base64_encode($html)) . "\r\n--$boundary--\r\n";
$sent = @mail(TO_EMAIL, $enc($subject), $body, $headers, '-f' . FROM_EMAIL);
if (!$sent) { http_response_code(500); reply(false, 'mail'); }

/* ---------- Accusé de réception client ---------- */
$ack = [
 'fr' => ["Votre demande de réservation — " . SITE_NAME, "Bonjour $name,\n\nNous avons bien reçu votre demande de réservation :\n\n  $apartment\n  du " . $fr($dIn) . " au " . $fr($dOut) . " ($nights nuit" . ($nights>1?'s':'') . ", $guests personne" . ($guests>1?'s':'') . ")\n\nAucun paiement n’a été effectué pour le moment. " . HOST_NAME . " vérifie la disponibilité et vous répond sous 24 h avec le tarif (−10 % par rapport aux plateformes, frais de ménage offerts) et un lien de paiement sécurisé pour l’acompte. Votre réservation sera confirmée à réception de l’acompte.\n\nUne question ? Répondez simplement à cet email ou écrivez-nous sur WhatsApp au " . HOST_PHONE . ".\n\nÀ très bientôt à Lumbarda,\n" . SITE_NAME . "\nhttps://lesterrasses-lumbarda.com"],
 'en' => ["Your booking request — " . SITE_NAME, "Hello $name,\n\nWe have received your booking request:\n\n  $apartment\n  from " . $fr($dIn) . " to " . $fr($dOut) . " ($nights night" . ($nights>1?'s':'') . ", $guests guest" . ($guests>1?'s':'') . ")\n\nNo payment has been taken yet. " . HOST_NAME . " is checking availability and will reply within 24 hours with the price (10% less than booking platforms, no cleaning fee) and a secure payment link for the deposit. Your booking is confirmed once the deposit is received.\n\nAny question? Just reply to this email or message us on WhatsApp at " . HOST_PHONE . ".\n\nSee you soon in Lumbarda,\n" . SITE_NAME . "\nhttps://lesterrasses-lumbarda.com"],
 'it' => ["La vostra richiesta di prenotazione — " . SITE_NAME, "Buongiorno $name,\n\nAbbiamo ricevuto la vostra richiesta di prenotazione:\n\n  $apartment\n  dal " . $fr($dIn) . " al " . $fr($dOut) . " ($nights notti, $guests persone)\n\nNessun pagamento è stato effettuato per ora. " . HOST_NAME . " verifica la disponibilità e vi risponde entro 24 ore con il prezzo (−10% rispetto alle piattaforme, pulizie incluse) e un link di pagamento sicuro per l’acconto. La prenotazione è confermata al ricevimento dell’acconto.\n\nDomande? Rispondete a questa email o scriveteci su WhatsApp al " . HOST_PHONE . ".\n\nA presto a Lumbarda,\n" . SITE_NAME . "\nhttps://lesterrasses-lumbarda.com"],
 'de' => ["Ihre Buchungsanfrage — " . SITE_NAME, "Guten Tag $name,\n\nwir haben Ihre Buchungsanfrage erhalten:\n\n  $apartment\n  vom " . $fr($dIn) . " bis " . $fr($dOut) . " ($nights Nächte, $guests Personen)\n\nEs wurde noch keine Zahlung vorgenommen. " . HOST_NAME . " prüft die Verfügbarkeit und antwortet innerhalb von 24 Stunden mit dem Preis (10 % günstiger als auf Buchungsplattformen, ohne Reinigungsgebühr) und einem sicheren Zahlungslink für die Anzahlung. Ihre Buchung ist nach Eingang der Anzahlung bestätigt.\n\nFragen? Antworten Sie einfach auf diese E-Mail oder schreiben Sie uns per WhatsApp an " . HOST_PHONE . ".\n\nBis bald in Lumbarda,\n" . SITE_NAME . "\nhttps://lesterrasses-lumbarda.com"],
 'hr' => ["Vaš zahtjev za rezervaciju — " . SITE_NAME, "Poštovani $name,\n\nprimili smo vaš zahtjev za rezervaciju:\n\n  $apartment\n  od " . $fr($dIn) . " do " . $fr($dOut) . " ($nights noći, $guests osobe)\n\nZasad nije izvršeno nikakvo plaćanje. " . HOST_NAME . " provjerava dostupnost i odgovara vam u roku od 24 sata s cijenom (10 % niže nego na platformama, bez naknade za čišćenje) i sigurnom poveznicom za plaćanje predujma. Rezervacija je potvrđena po primitku predujma.\n\nPitanja? Odgovorite na ovaj e-mail ili nam pišite na WhatsApp " . HOST_PHONE . ".\n\nVidimo se u Lumbardi,\n" . SITE_NAME . "\nhttps://lesterrasses-lumbarda.com"],
];
[$ackSubject, $ackBody] = $ack[$lang] ?? $ack['fr'];
$ackHeaders = "From: " . SITE_NAME . " <" . FROM_EMAIL . ">\r\nReply-To: " . TO_EMAIL . "\r\nMIME-Version: 1.0\r\nContent-Type: text/plain; charset=UTF-8\r\nContent-Transfer-Encoding: 8bit\r\n";
@mail($email, $enc($ackSubject), $ackBody, $ackHeaders, '-f' . FROM_EMAIL);

reply(true);
