<?php
$devicesFile = 'devices.json';

if (file_exists($devicesFile)) {
    $devices = json_decode(file_get_contents($devicesFile), true);
} else {
    $devices = [];
}

header('Content-Type: application/json');
echo json_encode($devices);
?>
