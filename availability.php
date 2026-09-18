<?php
/**
 * Les Terrasses de Lumbarda — disponibilités par appartement
 *
 * Lit les calendriers iCal (Booking.com, Airbnb ou Smoobu) de chaque appartement,
 * les fusionne et renvoie les périodes occupées en JSON au calendrier du site.
 * Résultat mis en cache 15 minutes pour ne pas solliciter les plateformes.
 *
 * Les liens iCal sont dans availability-config.php (jamais exposé au public).
 */

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: public, max-age=300');

const CACHE_TTL = 900; // secondes

$configFile = __DIR__ . '/availability-config.php';
$feeds = is_file($configFile) ? (require $configFile) : [];
$units = ['apt1' => [], 'apt2' => [], 'apt3' => [], 'apt4' => []];

$configured = false;
foreach ($units as $id => $_) {
    if (!empty($feeds[$id])) { $configured = true; }
}
if (!$configured) {
    echo json_encode(['ok' => true, 'configured' => false, 'units' => $units]);
    exit;
}

$cacheFile = sys_get_temp_dir() . '/lt_availability_' . md5(json_encode($feeds)) . '.json';
if (is_file($cacheFile) && filemtime($cacheFile) > time() - CACHE_TTL) {
    readfile($cacheFile);
    exit;
}

function fetchIcal(string $url): ?string {
    if (!preg_match('#^https://#i', $url)) { return null; }
    $ctx = stream_context_create([
        'http'  => ['timeout' => 8, 'user_agent' => 'LesTerrassesLumbarda/1.0', 'follow_location' => 1, 'max_redirects' => 3],
        'https' => ['timeout' => 8],
    ]);
    $data = @file_get_contents($url, false, $ctx, 0, 2000000);
    return ($data !== false && stripos($data, 'BEGIN:VCALENDAR') !== false) ? $data : null;
}

/** @return array<int,array{0:string,1:string}> périodes [arrivée, départ) au format AAAA-MM-JJ */
function parseIcal(string $ics): array {
    $ics = preg_replace("/\r?\n[ \t]/", '', $ics); // lignes repliées
    $out = [];
    if (!preg_match_all('/BEGIN:VEVENT(.*?)END:VEVENT/s', $ics, $events)) { return $out; }
    foreach ($events[1] as $ev) {
        if (!preg_match('/^DTSTART[^:\n]*:(\d{8})/m', $ev, $s)) { continue; }
        if (!preg_match('/^DTEND[^:\n]*:(\d{8})/m', $ev, $e)) { $e = [1 => $s[1]]; }
        if (preg_match('/^STATUS:CANCELLED/mi', $ev)) { continue; }
        $start = substr($s[1], 0, 4) . '-' . substr($s[1], 4, 2) . '-' . substr($s[1], 6, 2);
        $end   = substr($e[1], 0, 4) . '-' . substr($e[1], 4, 2) . '-' . substr($e[1], 6, 2);
        if ($end <= $start) { $end = date('Y-m-d', strtotime($start . ' +1 day')); }
        if ($end < date('Y-m-d')) { continue; } // passé
        $out[] = [$start, $end];
    }
    return $out;
}

$failed = false;
foreach ($units as $id => $_) {
    $ranges = [];
    foreach ((array)($feeds[$id] ?? []) as $url) {
        $ics = fetchIcal((string)$url);
        if ($ics === null) { $failed = true; continue; }
        $ranges = array_merge($ranges, parseIcal($ics));
    }
    usort($ranges, fn($a, $b) => strcmp($a[0], $b[0]));
    $units[$id] = $ranges;
}

$json = json_encode(['ok' => !$failed, 'configured' => true, 'units' => $units, 'updated' => date('c')]);
if (!$failed) { @file_put_contents($cacheFile, $json, LOCK_EX); }
elseif (is_file($cacheFile)) { readfile($cacheFile); exit; } // dernière version connue
echo $json;
