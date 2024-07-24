<?php
$input = json_decode(file_get_contents('php://input'), true);
$devicesFile = 'devices.json';

if (!isset($input['id'])) {
    echo json_encode(['success' => false, 'message' => 'Device ID not provided']);
    exit;
}

$deviceId = $input['id'];

if (file_exists($devicesFile)) {
    $devices = json_decode(file_get_contents($devicesFile), true);
} else {
    echo json_encode(['success' => false, 'message' => 'Devices file not found']);
    exit;
}

// Remove the device with the matching ID
$devices = array_filter($devices, function($device) use ($deviceId) {
    return $device['id'] !== $deviceId;
});

// Re-index the array
$devices = array_values($devices);

// Save the updated devices list back to the file
file_put_contents($devicesFile, json_encode($devices));

echo json_encode(['success' => true]);
?>
