<?php
/**
 * Debug-Script für Server-Monitoring
 * Führen Sie dies auf dem Debian-Server aus
 */

echo "=== SERVER-MONITORING DEBUG ===\n\n";

// 1. System-Informationen
echo "1. SYSTEM-INFO:\n";
echo "OS: " . PHP_OS_FAMILY . "\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Load Average: " . implode(', ', sys_getloadavg()) . "\n";
echo "CPU Count: " . shell_exec('nproc') . "\n\n";

// 2. Berechtigungen testen
echo "2. PERMISSIONS:\n";
echo "shell_exec available: " . (function_exists('shell_exec') ? 'YES' : 'NO') . "\n";
echo "file_get_contents /proc/stat: " . (file_get_contents('/proc/stat') ? 'READABLE' : 'NOT READABLE') . "\n";
echo "file_get_contents /proc/meminfo: " . (file_get_contents('/proc/meminfo') ? 'READABLE' : 'NOT READABLE') . "\n";
echo "file_get_contents /proc/uptime: " . (file_get_contents('/proc/uptime') ? 'READABLE' : 'NOT READABLE') . "\n\n";

// 3. CPU-Test
echo "3. CPU-TEST:\n";
$load = sys_getloadavg();
$cpuCount = (int)shell_exec('nproc');
$cpuUsage = ($load[0] / $cpuCount) * 100;
echo "Load Average: " . $load[0] . "\n";
echo "CPU Count: " . $cpuCount . "\n";
echo "Calculated CPU Usage: " . round($cpuUsage, 2) . "%\n\n";

// 4. Memory-Test
echo "4. MEMORY-TEST:\n";
$memInfo = file_get_contents('/proc/meminfo');
preg_match('/MemTotal:\s+(\d+)/', $memInfo, $totalMatches);
preg_match('/MemAvailable:\s+(\d+)/', $memInfo, $availableMatches);

if (empty($availableMatches)) {
    preg_match('/MemFree:\s+(\d+)/', $memInfo, $freeMatches);
    preg_match('/Buffers:\s+(\d+)/', $memInfo, $buffersMatches);
    preg_match('/Cached:\s+(\d+)/', $memInfo, $cachedMatches);
    
    $total = isset($totalMatches[1]) ? (int)$totalMatches[1] * 1024 : 0;
    $free = isset($freeMatches[1]) ? (int)$freeMatches[1] * 1024 : 0;
    $buffers = isset($buffersMatches[1]) ? (int)$buffersMatches[1] * 1024 : 0;
    $cached = isset($cachedMatches[1]) ? (int)$cachedMatches[1] * 1024 : 0;
    
    $available = $free + $buffers + $cached;
} else {
    $total = isset($totalMatches[1]) ? (int)$totalMatches[1] * 1024 : 0;
    $available = isset($availableMatches[1]) ? (int)$availableMatches[1] * 1024 : 0;
}

$used = $total - $available;
$percentage = $total > 0 ? round(($used / $total) * 100, 2) : 0;

echo "Total Memory: " . number_format($total / 1024 / 1024, 2) . " MB\n";
echo "Used Memory: " . number_format($used / 1024 / 1024, 2) . " MB\n";
echo "Available Memory: " . number_format($available / 1024 / 1024, 2) . " MB\n";
echo "Memory Usage: " . $percentage . "%\n\n";

// 5. Disk-Test
echo "5. DISK-TEST:\n";
$output = shell_exec('df -B1');
$lines = explode("\n", $output);
$disks = [];

foreach ($lines as $line) {
    if (preg_match('/^\/dev\/(\S+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)%\s+(.+)$/', $line, $matches)) {
        $disks[] = [
            'device' => $matches[1],
            'total' => (int)$matches[2],
            'used' => (int)$matches[3],
            'free' => (int)$matches[4],
            'percentage' => (int)$matches[5]
        ];
    }
}

foreach ($disks as $disk) {
    echo "Device: " . $disk['device'] . "\n";
    echo "  Total: " . number_format($disk['total'] / 1024 / 1024, 2) . " MB\n";
    echo "  Used: " . number_format($disk['used'] / 1024 / 1024, 2) . " MB\n";
    echo "  Free: " . number_format($disk['free'] / 1024 / 1024, 2) . " MB\n";
    echo "  Usage: " . $disk['percentage'] . "%\n\n";
}

// 6. Uptime-Test
echo "6. UPTIME-TEST:\n";
$uptime = file_get_contents('/proc/uptime');
$uptimeSeconds = (int)explode(' ', $uptime)[0];
$days = floor($uptimeSeconds / 86400);
$hours = floor(($uptimeSeconds % 86400) / 3600);
$minutes = floor(($uptimeSeconds % 3600) / 60);

echo "Uptime: " . $days . " days, " . $hours . " hours, " . $minutes . " minutes\n";
echo "Uptime Seconds: " . $uptimeSeconds . "\n\n";

// 7. JSON-Output für API-Test
echo "7. JSON-OUTPUT:\n";
$data = [
    'cpu' => round($cpuUsage, 2),
    'memory' => [
        'total' => $total,
        'used' => $used,
        'free' => $available,
        'percentage' => $percentage
    ],
    'disk' => $disks,
    'uptime' => $uptimeSeconds,
    'load_average' => $load,
    'timestamp' => date('c')
];

echo json_encode($data, JSON_PRETTY_PRINT) . "\n\n";

echo "=== DEBUG COMPLETE ===\n";
?> 