<?php
// ldp-api.php — LibreDesigoPulse API proxy.
// Keeps the LibreNMS API token server-side so it NEVER appears in page source.
// Place next to topology-multi.html in /opt/librenms/html/plugins/.
// Lock down: token below + device allowlist + read-only endpoints only.
declare(strict_types=1);

const LDP_API_TOKEN = 'PASTE_TOKEN_HERE';   // server-side only, never sent to the browser
const LDP_API_BASE  = 'http://localhost';
const LDP_DEVICES   = [                     // must match CONTROLLERS in topology-multi.html
  '172.31.255.89',
  '172.31.255.90',
  '172.31.255.91',
];

header('Content-Type: application/json');

$what = $_GET['what'] ?? '';
$dev  = $_GET['dev'] ?? '';

if (!in_array($dev, LDP_DEVICES, true)) {
  http_response_code(403);
  echo json_encode(['status' => 'error', 'message' => 'device not allowed']);
  exit;
}

if ($what === 'services') {
  $path = '/api/v0/services/' . rawurlencode($dev);
} elseif ($what === 'eventlog') {
  $limit = min(2000, max(1, (int)($_GET['limit'] ?? 500)));
  $path = '/api/v0/logs/eventlog/' . rawurlencode($dev) . '?limit=' . $limit;
} else {
  http_response_code(400);
  echo json_encode(['status' => 'error', 'message' => 'bad what']);
  exit;
}

$ch = curl_init(LDP_API_BASE . $path);
curl_setopt_array($ch, [
  CURLOPT_HTTPHEADER     => ['X-Auth-Token: ' . LDP_API_TOKEN],
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_TIMEOUT        => 20,
]);
$body = curl_exec($ch);
$err  = curl_error($ch);
curl_close($ch);

if ($body === false) {
  http_response_code(502);
  echo json_encode(['status' => 'error', 'message' => 'upstream failed: ' . $err]);
  exit;
}
echo $body;
