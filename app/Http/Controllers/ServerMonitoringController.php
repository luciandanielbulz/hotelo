<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            'database' => $this->getDatabaseInfo(),
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
            // Linux-Implementierung - Schnellere Version
            try {
                // Verwende Load Average als Näherung für CPU-Auslastung
                $load = sys_getloadavg();
                $cpuCount = (int)shell_exec('nproc');
                
                if ($cpuCount > 0) {
                    // Load Average pro CPU-Kern
                    $cpuUsage = ($load[0] / $cpuCount) * 100;
                    return min(round($cpuUsage, 2), 100); // Maximal 100%
                }
                
                return 0;
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
            // Linux-Implementierung - Verbesserte Version
            try {
                $memInfo = @file_get_contents('/proc/meminfo');
                if (!$memInfo) {
                    return [
                        'total' => 0,
                        'used' => 0,
                        'free' => 0,
                        'percentage' => 0
                    ];
                }
                
                preg_match('/MemTotal:\s+(\d+)/', $memInfo, $totalMatches);
                preg_match('/MemAvailable:\s+(\d+)/', $memInfo, $availableMatches);
                
                // Fallback für ältere Kernel
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
                
                return [
                    'total' => $total,
                    'used' => $used,
                    'free' => $available,
                    'percentage' => $total > 0 ? round(($used / $total) * 100, 2) : 0
                ];
            } catch (Exception $e) {
                return [
                    'total' => 0,
                    'used' => 0,
                    'free' => 0,
                    'percentage' => 0
                ];
            }
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

    /**
     * Datenbank-Informationen abrufen
     */
    private function getDatabaseInfo()
    {
        try {
            $connection = DB::connection();
            $pdo = $connection->getPdo();
            
            // Datenbank-Name
            $databaseName = $connection->getDatabaseName();
            
            // Datenbank-Version
            $version = $pdo->getAttribute(\PDO::ATTR_SERVER_VERSION);
            
            // Verbindungsstatus
            $connected = $pdo !== null;
            
            // Tabellenanzahl
            $tableCount = 0;
            try {
                if ($driver === 'mysql' || $driver === 'mariadb') {
                    $tables = DB::select("SHOW TABLES");
                    $tableCount = count($tables);
                } elseif ($driver === 'pgsql') {
                    $tables = DB::select("SELECT tablename FROM pg_tables WHERE schemaname = 'public'");
                    $tableCount = count($tables);
                } elseif ($driver === 'sqlite') {
                    $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
                    $tableCount = count($tables);
                } else {
                    // Fallback: Versuche Schema zu verwenden
                    $tables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = ?", [$databaseName]);
                    $tableCount = count($tables);
                }
            } catch (\Exception $e) {
                // Tabellenanzahl konnte nicht ermittelt werden
            }
            
            // Datenbank-Größe (MySQL/MariaDB)
            $size = 0;
            $sizeFormatted = 'N/A';
            
            try {
                $result = DB::select("SELECT 
                    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
                    FROM information_schema.tables 
                    WHERE table_schema = ?", [$databaseName]);
                
                if (!empty($result) && isset($result[0]->size_mb)) {
                    $size = (float)$result[0]->size_mb;
                    $sizeFormatted = number_format($size, 2) . ' MB';
                    
                    if ($size > 1024) {
                        $sizeFormatted = number_format($size / 1024, 2) . ' GB';
                    }
                }
            } catch (\Exception $e) {
                // Größenberechnung nicht möglich (z.B. SQLite)
            }
            
            // Verbindungs-Typ
            $driver = $connection->getDriverName();
            
            // Maximale Verbindungen (MySQL)
            $maxConnections = null;
            $currentConnections = null;
            
            try {
                $maxResult = DB::select("SHOW VARIABLES LIKE 'max_connections'");
                if (!empty($maxResult)) {
                    $maxConnections = (int)$maxResult[0]->Value;
                }
                
                $currentResult = DB::select("SHOW STATUS LIKE 'Threads_connected'");
                if (!empty($currentResult)) {
                    $currentConnections = (int)$currentResult[0]->Value;
                }
            } catch (\Exception $e) {
                // Nicht verfügbar für alle Datenbanktypen
            }
            
            return [
                'connected' => $connected,
                'driver' => ucfirst($driver),
                'version' => $version,
                'database_name' => $databaseName,
                'table_count' => $tableCount,
                'size_mb' => $size,
                'size_formatted' => $sizeFormatted,
                'max_connections' => $maxConnections,
                'current_connections' => $currentConnections,
            ];
        } catch (\Exception $e) {
            return [
                'connected' => false,
                'driver' => 'Unknown',
                'version' => 'N/A',
                'database_name' => 'N/A',
                'table_count' => 0,
                'size_mb' => 0,
                'size_formatted' => 'N/A',
                'max_connections' => null,
                'current_connections' => null,
                'error' => $e->getMessage(),
            ];
        }
    }
} 