<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServerMonitoringController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view_server_monitoring');
    }

    /**
     * Zeige die Server-Monitoring-Seite
     */
    public function index()
    {
        return view('server-monitoring.index');
    }

    /**
     * API-Endpunkt für Server-Daten (für AJAX-Calls)
     */
    public function getServerData()
    {
        $data = [
            'cpu' => $this->getCpuUsage(),
            'memory' => $this->getMemoryUsage(),
            'disk' => $this->getDiskUsage(),
            'uptime' => $this->getUptime(),
            'load_average' => $this->getLoadAverage(),
            'network' => $this->getNetworkStats(),
            'timestamp' => now()->toISOString(),
        ];

        return response()->json($data);
    }

    /**
     * CPU-Auslastung abrufen
     */
    private function getCpuUsage()
    {
        if (PHP_OS_FAMILY === 'Windows') {
            // Windows-Implementierung
            $output = shell_exec('wmic cpu get loadpercentage /value');
            preg_match('/LoadPercentage=(\d+)/', $output, $matches);
            return isset($matches[1]) ? (int)$matches[1] : 0;
        } else {
            // Linux-Implementierung - Sicherere Version
            try {
                $stat1 = @file_get_contents('/proc/stat');
                if (!$stat1) {
                    return 0;
                }
                
                preg_match('/cpu\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)/', $stat1, $matches1);
                
                if (empty($matches1)) {
                    return 0;
                }
                
                $total1 = array_sum(array_slice($matches1, 1));
                $idle1 = $matches1[4];
                
                sleep(1);
                
                $stat2 = @file_get_contents('/proc/stat');
                if (!$stat2) {
                    return 0;
                }
                
                preg_match('/cpu\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)/', $stat2, $matches2);
                
                if (empty($matches2)) {
                    return 0;
                }
                
                $total2 = array_sum(array_slice($matches2, 1));
                $idle2 = $matches2[4];
                
                $totalDiff = $total2 - $total1;
                $idleDiff = $idle2 - $idle1;
                
                if ($totalDiff == 0) {
                    return 0;
                }
                
                $cpuUsage = (($totalDiff - $idleDiff) / $totalDiff) * 100;
                return round($cpuUsage, 2);
            } catch (Exception $e) {
                return 0;
            }
        }
    }

    /**
     * Speicherauslastung abrufen
     */
    private function getMemoryUsage()
    {
        if (PHP_OS_FAMILY === 'Windows') {
            // Windows-Implementierung
            $output = shell_exec('wmic OS get TotalVisibleMemorySize,FreePhysicalMemory /value');
            preg_match('/TotalVisibleMemorySize=(\d+)/', $output, $totalMatches);
            preg_match('/FreePhysicalMemory=(\d+)/', $output, $freeMatches);
            
            $total = isset($totalMatches[1]) ? (int)$totalMatches[1] : 0;
            $free = isset($freeMatches[1]) ? (int)$freeMatches[1] : 0;
            $used = $total - $free;
            
            return [
                'total' => $total * 1024, // KB zu Bytes
                'used' => $used * 1024,
                'free' => $free * 1024,
                'percentage' => $total > 0 ? round(($used / $total) * 100, 2) : 0
            ];
        } else {
            // Linux-Implementierung
            $memInfo = file_get_contents('/proc/meminfo');
            preg_match('/MemTotal:\s+(\d+)/', $memInfo, $totalMatches);
            preg_match('/MemFree:\s+(\d+)/', $memInfo, $freeMatches);
            preg_match('/Buffers:\s+(\d+)/', $memInfo, $buffersMatches);
            preg_match('/Cached:\s+(\d+)/', $memInfo, $cachedMatches);
            
            $total = isset($totalMatches[1]) ? (int)$totalMatches[1] * 1024 : 0;
            $free = isset($freeMatches[1]) ? (int)$freeMatches[1] * 1024 : 0;
            $buffers = isset($buffersMatches[1]) ? (int)$buffersMatches[1] * 1024 : 0;
            $cached = isset($cachedMatches[1]) ? (int)$cachedMatches[1] * 1024 : 0;
            
            $available = $free + $buffers + $cached;
            $used = $total - $available;
            
            return [
                'total' => $total,
                'used' => $used,
                'free' => $available,
                'percentage' => $total > 0 ? round(($used / $total) * 100, 2) : 0
            ];
        }
    }

    /**
     * Festplattenauslastung abrufen
     */
    private function getDiskUsage()
    {
        if (PHP_OS_FAMILY === 'Windows') {
            // Windows-Implementierung
            $output = shell_exec('wmic logicaldisk get size,freespace,caption /value');
            $lines = explode("\n", $output);
            $disks = [];
            
            foreach ($lines as $line) {
                if (strpos($line, 'Caption=') !== false) {
                    preg_match('/Caption=(\w+):/', $line, $captionMatches);
                    $caption = isset($captionMatches[1]) ? $captionMatches[1] : '';
                    
                    // Suche nach den zugehörigen Größen-Informationen
                    foreach ($lines as $sizeLine) {
                        if (strpos($sizeLine, 'Size=') !== false && strpos($sizeLine, $caption) !== false) {
                            preg_match('/Size=(\d+)/', $sizeLine, $sizeMatches);
                            $total = isset($sizeMatches[1]) ? (int)$sizeMatches[1] : 0;
                            
                            foreach ($lines as $freeLine) {
                                if (strpos($freeLine, 'FreeSpace=') !== false && strpos($freeLine, $caption) !== false) {
                                    preg_match('/FreeSpace=(\d+)/', $freeLine, $freeMatches);
                                    $free = isset($freeMatches[1]) ? (int)$freeMatches[1] : 0;
                                    $used = $total - $free;
                                    
                                    $disks[] = [
                                        'device' => $caption . ':',
                                        'total' => $total,
                                        'used' => $used,
                                        'free' => $free,
                                        'percentage' => $total > 0 ? round(($used / $total) * 100, 2) : 0
                                    ];
                                    break 2;
                                }
                            }
                        }
                    }
                }
            }
            
            return $disks;
        } else {
            // Linux-Implementierung - Sicherere Version ohne sudo
            try {
                $output = @shell_exec('df -B1');
                if (!$output) {
                    // Fallback: Versuchen Sie ohne sudo
                    $output = @shell_exec('df');
                }
                
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
                
                return $disks;
            } catch (Exception $e) {
                return [];
            }
        }
    }

    /**
     * Uptime abrufen
     */
    private function getUptime()
    {
        if (PHP_OS_FAMILY === 'Windows') {
            // Windows-Implementierung
            $output = shell_exec('wmic os get lastbootuptime /value');
            preg_match('/LastBootUpTime=(\d{14})/', $output, $matches);
            
            if (isset($matches[1])) {
                $bootTime = \DateTime::createFromFormat('YmdHis', $matches[1]);
                $uptime = time() - $bootTime->getTimestamp();
                return $uptime;
            }
            
            return 0;
        } else {
            // Linux-Implementierung
            $uptime = file_get_contents('/proc/uptime');
            return (int)explode(' ', $uptime)[0];
        }
    }

    /**
     * Load Average abrufen
     */
    private function getLoadAverage()
    {
        if (PHP_OS_FAMILY === 'Windows') {
            // Windows hat kein Load Average, verwende CPU-Auslastung
            return [0, 0, 0];
        } else {
            // Linux-Implementierung
            $load = sys_getloadavg();
            return $load;
        }
    }

    /**
     * Netzwerk-Statistiken abrufen
     */
    private function getNetworkStats()
    {
        if (PHP_OS_FAMILY === 'Windows') {
            // Windows-Implementierung
            $output = shell_exec('netstat -e');
            $lines = explode("\n", $output);
            
            foreach ($lines as $line) {
                if (preg_match('/\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)/', $line, $matches)) {
                    return [
                        'bytes_received' => (int)$matches[1],
                        'bytes_sent' => (int)$matches[2]
                    ];
                }
            }
            
            return ['bytes_received' => 0, 'bytes_sent' => 0];
        } else {
            // Linux-Implementierung
            $netDev = file_get_contents('/proc/net/dev');
            $lines = explode("\n", $netDev);
            $totalReceived = 0;
            $totalSent = 0;
            
            foreach ($lines as $line) {
                if (preg_match('/^\s*(\w+):\s+(\d+)\s+\d+\s+\d+\s+\d+\s+\d+\s+\d+\s+\d+\s+\d+\s+(\d+)/', $line, $matches)) {
                    $interface = $matches[1];
                    if ($interface !== 'lo') { // Loopback-Interface ausschließen
                        $totalReceived += (int)$matches[2];
                        $totalSent += (int)$matches[3];
                    }
                }
            }
            
            return [
                'bytes_received' => $totalReceived,
                'bytes_sent' => $totalSent
            ];
        }
    }
} 