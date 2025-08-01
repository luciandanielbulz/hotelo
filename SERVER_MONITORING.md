# Server-Monitoring System

## Übersicht

Das Server-Monitoring-System ermöglicht die Überwachung wichtiger Systemressourcen wie CPU, Speicher, Festplatte und Netzwerk in Echtzeit. Die Funktion ist nur für Benutzer mit der entsprechenden Berechtigung verfügbar.

## Funktionen

### Überwachte Metriken

1. **CPU-Auslastung**
   - Echtzeit-CPU-Auslastung in Prozent
   - Farbkodierung: Grün (<50%), Gelb (50-80%), Rot (>80%)

2. **Speicherauslastung**
   - Gesamter, verwendeter und freier Speicher
   - Auslastung in Prozent mit Farbkodierung
   - Anzeige in lesbaren Einheiten (B, KB, MB, GB, TB)

3. **Festplattenauslastung**
   - Alle verfügbaren Festplatten/Partitionen
   - Verwendeter, freier und Gesamtspeicherplatz
   - Auslastung in Prozent mit Farbkodierung

4. **Uptime**
   - Systemlaufzeit in Tagen, Stunden und Minuten
   - Zeigt an, wie lange das System ohne Neustart läuft

5. **Load Average (nur Linux)**
   - 1-Minuten, 5-Minuten und 15-Minuten Load Average
   - Wichtig für die Bewertung der Systemlast

6. **Netzwerk-Statistiken**
   - Empfangene und gesendete Bytes
   - Gesamte Netzwerkaktivität

7. **System-Informationen**
   - Betriebssystem (Windows/Linux)
   - PHP-Version
   - Laravel-Version
   - Zeitzone

## Installation

### 1. Migration ausführen

```bash
php artisan migrate
```

### 2. Seeder ausführen

```bash
php artisan db:seed --class=ServerMonitoringPermissionSeeder
```

### 3. Berechtigungen zuweisen

Die Berechtigung `view_server_monitoring` wird automatisch Admin-Rollen zugewiesen. Für andere Benutzer können Sie die Berechtigung manuell über die Rollenverwaltung zuweisen.

## Verwendung

### Zugriff

1. Melden Sie sich mit einem Benutzer an, der die Berechtigung `view_server_monitoring` hat
2. Navigieren Sie zu **Administration** → **Server-Monitoring**
3. Die Seite zeigt automatisch alle verfügbaren Metriken an

### Auto-Refresh

Die Daten werden automatisch alle 30 Sekunden aktualisiert. Sie können auch manuell über den "Aktualisieren"-Button neue Daten abrufen.

## Technische Details

### Betriebssystem-Unterstützung

#### Windows
- CPU-Auslastung über `wmic cpu get loadpercentage`
- Speicherauslastung über `wmic OS get TotalVisibleMemorySize,FreePhysicalMemory`
- Festplattenauslastung über `wmic logicaldisk get size,freespace,caption`
- Uptime über `wmic os get lastbootuptime`
- Netzwerk-Statistiken über `netstat -e`

#### Linux (Debian/Ubuntu)
- CPU-Auslastung über `sys_getloadavg()` und `nproc`
- Speicherauslastung über `/proc/meminfo`
- Festplattenauslastung über `df -B1`
- Uptime über `/proc/uptime`
- Load Average über `sys_getloadavg()`
- Netzwerk-Statistiken über `/proc/net/dev`

### Sicherheit

- Alle Routen sind mit der Berechtigung `view_server_monitoring` geschützt
- Middleware `CheckPermission` prüft die Berechtigung bei jedem Zugriff
- Nur authentifizierte Benutzer können auf die Funktion zugreifen

### Performance

- Daten werden bei Bedarf abgerufen (nicht gespeichert)
- Auto-Refresh alle 30 Sekunden
- Asynchrone Datenabfrage über AJAX
- Fehlerbehandlung für nicht verfügbare Metriken

## Fehlerbehebung

### Häufige Probleme

1. **"Verbindungsfehler"**
   - Prüfen Sie, ob die Shell-Befehle verfügbar sind
   - Stellen Sie sicher, dass `shell_exec()` nicht deaktiviert ist

2. **"Fehler beim Laden der Server-Daten"**
   - Prüfen Sie die Berechtigungen für die Ausführung von Systembefehlen
   - Überprüfen Sie die Logs unter `storage/logs/laravel.log`

3. **Fehlende Metriken**
   - Einige Metriken sind betriebssystemabhängig
   - Load Average ist nur unter Linux verfügbar
   - Windows zeigt keine Load Average-Werte an

### Debugging

Aktivieren Sie das Debug-Logging in `config/app.php`:

```php
'debug' => true,
```

Überprüfen Sie die Laravel-Logs:

```bash
tail -f storage/logs/laravel.log
```

## Anpassungen

### Neue Metriken hinzufügen

1. Erweitern Sie die `getServerData()` Methode in `ServerMonitoringController`
2. Fügen Sie die entsprechende View-Logik in der Livewire-Komponente hinzu
3. Aktualisieren Sie die Blade-Template

### Refresh-Intervall ändern

Ändern Sie das Intervall in `resources/views/server-monitoring/index.blade.php`:

```javascript
setInterval(function() {
    @this.call('refreshData');
}, 60000); // 60 Sekunden
```

### Farbkodierung anpassen

Die Farbkodierung kann in der Livewire-Komponente angepasst werden:

```php
public function getCpuColor($usage)
{
    if ($usage < 30) return 'text-green-600';  // Niedrig
    if ($usage < 70) return 'text-yellow-600'; // Mittel
    return 'text-red-600';                      // Hoch
}
```

## Support

Bei Problemen oder Fragen wenden Sie sich an den Systemadministrator oder erstellen Sie ein Issue im Projekt-Repository. 