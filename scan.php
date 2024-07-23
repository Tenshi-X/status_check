<?php
set_time_limit(60); // Set limit to 60 seconds to handle longer scans

function logMessage($message) {
    $logFile = 'scan.log';
    $current = file_get_contents($logFile);
    $current .= $message . "\n";
    file_put_contents($logFile, $current);
}

function ping($ip) {
    $output = [];
    $result = shell_exec("ping -n 1 " . escapeshellarg($ip)); // Adjust this for Windows
    if (strpos($result, 'Reply from') !== false || strpos($result, '1 received') !== false) {
        return "200 (Success)";
    } else {
        return "500 (Bad Request)";
    }
}

function scanNetwork($subnet) {
    logMessage("Start scanning network at " . date('Y-m-d H:i:s'));
    $output = shell_exec("nmap -sn " . escapeshellarg($subnet));
    logMessage("Nmap output:\n" . $output);
    $devices = [];
    preg_match_all('/Nmap scan report for ([\d\.]+)/', $output, $matches);
    if (!empty($matches[1])) {
        $devices = $matches[1];
    }
    logMessage("Devices found:\n" . print_r($devices, true));
    return $devices;
}

$subnet = "192.168.1.0/24"; // Ganti sesuai dengan subnet Anda
$devices = scanNetwork($subnet);
$deviceList = [];

foreach ($devices as $ip) {
    sleep(1); // Reduce sleep time for faster response
    $responseStatus = ping($ip);
    $status = (strpos($responseStatus, "200") !== false) ? "Active" : "Inactive";
    $statusClass = (strpos($responseStatus, "200") !== false) ? "status-active" : "status-inactive";
    $deviceList[] = [
        'name' => 'Device', // Nama perangkat bisa diganti dengan cara yang lebih spesifik
        'ip' => $ip,
        'responseStatus' => $responseStatus,
        'status' => $status,
        'statusClass' => $statusClass
    ];
}

logMessage("Finished scanning network at " . date('Y-m-d H:i:s'));
logMessage("Device list:\n" . print_r($deviceList, true));

header('Content-Type: application/json');
echo json_encode($deviceList);
