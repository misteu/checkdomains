<?php
header("Content-Type: text/event-stream; charset=utf-8");
header("Cache-Control: no-cache");
header("Connection: keep-alive");
header("X-Accel-Buffering: no");
while (ob_get_level()) ob_end_flush();
ob_implicit_flush(true);
ini_set('zlib.output_compression', 0);

// Get domain from query parameter
$domain = $_GET['domain'] ?? '';
if (!preg_match('/^[a-zA-Z0-9.-]+$/', $domain)) {
    echo "data: {\"domain\":\"$domain\",\"error_message\":\"Invalid domain\"}\n\n";
    exit;
}

// Run domain-check in streaming mode
$cmd = "/usr/bin/sudo /usr/local/bin/domain-check "
     . escapeshellarg($domain)
     . " --all --streaming --concurrency 20 2>&1";

$handle = popen($cmd, 'r');

if (!$handle) {
    echo "data: {\"domain\":\"$domain\",\"error_message\":\"Cannot run command\"}\n\n";
    exit;
}


// 🚨 VERY IMPORTANT: send a first event immediately
echo "data: " . json_encode(["status" => "started"]) . "\n\n";
flush();

while (!feof($handle)) {
    $line = fgets($handle);
    if (!$line) continue;

    $line = trim($line);

    // Try to extract domain + status
    if (preg_match('/\]\s+([^\s]+)\s+(AVAILABLE|UNKNOWN|TAKEN)/', $line, $m)) {
        $domain = $m[1];
        $status = $m[2];

        $available = null;
        if ($status === "AVAILABLE") $available = true;
        if ($status === "TAKEN") $available = false;

        $data = [
            "domain" => $domain,
            "available" => $available,
            "status" => $status
        ];

        echo "data: " . json_encode($data) . "\n\n";
    }

    ob_flush();
    flush();
}


pclose($handle);
