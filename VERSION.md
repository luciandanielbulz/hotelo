# quickBill Versionierung

## Übersicht

Dieses Projekt verwendet ein umfassendes Versionierungssystem mit Semantic Versioning (SemVer).

## Aktuelle Version: 1.0.0

## Setup

### 1. Umgebungsvariable setzen

Fügen Sie zu Ihrer `.env`-Datei hinzu:
```
APP_VERSION=1.0.0
```

### 2. Berechtigungen aktualisieren

```bash
php artisan db:seed --class=PermissionsSeeder
```

## Verwendung

### Artisan-Commands

#### Version anzeigen
```bash
php artisan version:manage show
```

#### Version setzen
```bash
php artisan version:manage set 1.1.0
```

#### Version erhöhen
```bash
# Patch (1.0.0 -> 1.0.1)
php artisan version:manage bump --type=patch

# Minor (1.0.0 -> 1.1.0)
php artisan version:manage bump --type=minor

# Major (1.0.0 -> 2.0.0)
php artisan version:manage bump --type=major
```

#### Build-Info erstellen
```bash
php artisan version:manage build
```

### In der Anwendung

#### Kompakte Anzeige
```blade
<x-version-info />
```

#### Detaillierte Anzeige
```blade
<x-version-info :show-detailed="true" />
```

#### Programmatisch
```php
use App\Services\VersionService;

$version = VersionService::getCurrentVersion();
$buildInfo = VersionService::getBuildInfo();
```

## Semantic Versioning

- **MAJOR** (X.0.0): Breaking Changes
- **MINOR** (1.X.0): Neue Features (rückwärtskompatibel)
- **PATCH** (1.0.X): Bugfixes (rückwärtskompatibel)

## Git-Integration

Das System integriert automatisch mit Git:
- Liest Git-Tags als Versionen
- Zeigt aktuellen Commit und Branch
- Kann automatisch Tags erstellen

## Deployment

Für Production-Deployments:

1. Version setzen:
   ```bash
   php artisan version:manage set 1.0.0
   ```

2. Git-Tag erstellen:
   ```bash
   git tag -a v1.0.0 -m "Release 1.0.0"
   git push origin v1.0.0
   ```

3. Build-Info erstellen:
   ```bash
   php artisan version:manage build
   ```

## API-Endpunkt

Version-Informationen als JSON:
```
GET /api/version
```

## Berechtigungen

- `view_system_info`: Berechtigung zum Anzeigen der Versionsinformationen

## Versionsinformationen-Seite

Unter `/version` finden Sie detaillierte Systeminformationen.

## Dateien

- `app/Services/VersionService.php`: Hauptlogik
- `app/Console/Commands/VersionCommand.php`: Artisan-Commands
- `app/View/Components/VersionInfo.php`: Blade-Component
- `app/Http/Controllers/VersionController.php`: Web-Controller
- `composer.json`: Version-Speicher
- `build-info.json`: Build-Informationen (automatisch generiert)

## Changelog

### 1.0.0 (2024-XX-XX)
- Versionierungssystem implementiert
- Semantic Versioning Support
- Git-Integration
- Web-Interface für Versionsinformationen
- Artisan-Commands für Versionsverwaltung 