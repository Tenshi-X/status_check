<?php
$input = json_decode(file_get_contents('php://input'), true);
$devicesFile = 'devices.json';

if (!isset($input['name']) || !isset($input['ip'])) {
    echo json_encode(['success' => false]);
    exit;
}

$name = $input['name'];
$ip = $input['ip'];

if (file_exists($devicesFile)) {
    $devices = json_decode(file_get_contents($devicesFile), true);
} else {
    $devices = [];
}

function ping($ip) {
    $os = PHP_OS_FAMILY;
    if ($os == 'Windows') {
        $result = shell_exec("ping -n 1 " . escapeshellarg($ip));
        if (strpos($result, 'Received = 1') !== false) {
            return "200 (Success)";
        } else {
            return "500 (Bad Request)";
        }
    } else {
        $result = shell_exec("ping -c 1 " . escapeshellarg($ip));
        if (strpos($result, '1 received') !== false) {
            return "200 (Success)";
        } else {
            return "500 (Bad Request)";
        }
    }
}

$responseStatus = ping($ip);
$status = (strpos($responseStatus, "200") !== false) ? "Active" : "Inactive";
$statusClass = (strpos($responseStatus, "200") !== false) ? "status-active" : "status-inactive";

$deviceId = uniqid(); // Generate a unique ID for the device

$devices[] = [
    'id' => $deviceId, // Add the ID to the device
    'name' => $name,
    'ip' => $ip,
    'responseStatus' => $responseStatus,
    'status' => $status,
    'statusClass' => $statusClass
];

file_put_contents($devicesFile, json_encode($devices));

echo json_encode(['success' => true]);
?>
