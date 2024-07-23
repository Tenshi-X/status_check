<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<pre>";
$output = shell_exec("C:\\Program Files (x86)\\Nmap\\nmap.exe -v 2>&1");
if ($output === null) {
    echo "shell_exec() returned null. This may indicate a problem with shell_exec being disabled or an issue with open_basedir.";
} else {
    echo "Output:\n$output";
}
echo "</pre>";
?>