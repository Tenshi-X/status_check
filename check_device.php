<?php
$input = json_decode(file_get_contents('php://input'), true);
$devicesFile = 'devices.json';

if (!isset($input['id']) || !isset($input['ip'])) {
    echo json_encode(['success' => false]);
    exit;
}

$id = $input['id'];
$ip = $input['ip'];

if (file_exists($devicesFile)) {
    $devices = json_decode(file_get_contents($devicesFile), true);
} else {
    echo json_encode(['success' => false]);
    exit;
}

function ping($ip) {
    $os = PHP_OS_FAMILY;
    if ($os == 'Windows') {
        $result = shell_exec("ping -n 1 " . escapeshellarg($ip));
        if (strpos($result, 'Received = 1') !== false) {
            return "200 (Success)";
        } else {
            return "404 (Not Found)";
        }
    } else {
        $result = shell_exec("ping -c 1 " . escapeshellarg($ip));
        if (strpos($result, '1 received') !== false) {
            return "200 (Success)";
        } else {
            return "404 (Not Found)";
        }
    }
}

$responseStatus = ping($ip);
$status = (strpos($responseStatus, "200") !== false) ? "Active" : "Inactive";
$statusClass = (strpos($responseStatus, "200") !== false) ? "status-active" : "status-inactive";

// Update the device in the list
foreach ($devices as &$device) {
    if ($device['id'] === $id) {
        $device['responseStatus'] = $responseStatus;
        $device['status'] = $status;
        $device['statusClass'] = $statusClass;
        break;
    }
}

file_put_contents($devicesFile, json_encode($devices));

echo json_encode([
    'success' => true,
    'status' => $status,
    'responseStatus' => $responseStatus,
    'statusClass' => $statusClass
]);
?>
