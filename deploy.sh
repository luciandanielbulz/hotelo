#!/bin/bash

# Deployment-Skript für Laravel quickBill
# Dieses Skript führt alle notwendigen Schritte für ein Production-Deployment aus

set -e  # Beende bei Fehlern

# Farben für Ausgabe
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}quickBill Deployment Script${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""

# Prüfe ob wir im richtigen Verzeichnis sind
if [ ! -f "artisan" ]; then
    echo -e "${RED}Fehler: artisan-Datei nicht gefunden. Bitte im Laravel-Projektverzeichnis ausführen.${NC}"
    exit 1
fi

# Prüfe ob .env existiert
if [ ! -f ".env" ]; then
    echo -e "${YELLOW}Warnung: .env-Datei nicht gefunden. Bitte erstellen Sie eine .env-Datei.${NC}"
    exit 1
fi

# 1. Composer Dependencies installieren
echo -e "${GREEN}[1/7] Composer Dependencies installieren...${NC}"
composer install --no-dev --optimize-autoloader
echo ""

# 2. NPM Dependencies (falls vorhanden)
if [ -f "package.json" ]; then
    echo -e "${GREEN}[2/7] NPM Dependencies installieren...${NC}"
    npm ci --production || npm install --production || echo -e "${YELLOW}NPM Install übersprungen${NC}"
    echo ""
    
    echo -e "${GREEN}[3/7] Assets bauen...${NC}"
    npm run build || echo -e "${YELLOW}Asset Build übersprungen${NC}"
    echo ""
else
    echo -e "${YELLOW}[2-3/7] package.json nicht gefunden, überspringe NPM${NC}"
    echo ""
fi

# 3. Migrationen ausführen
echo -e "${GREEN}[4/7] Datenbank-Migrationen ausführen...${NC}"
php artisan migrate --force
echo ""

# 4. Cache optimieren
echo -e "${GREEN}[5/7] Cache optimieren...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo ""

# 5. Storage Link erstellen (falls nicht vorhanden)
echo -e "${GREEN}[6/7] Storage Link prüfen...${NC}"
php artisan storage:link || echo -e "${YELLOW}Storage Link bereits vorhanden${NC}"
echo ""

# 6. Berechtigungen setzen
echo -e "${GREEN}[7/7] Berechtigungen setzen...${NC}"
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || echo -e "${YELLOW}Berechtigungen konnten nicht gesetzt werden (möglicherweise nicht als root)${NC}"
echo ""

# Deployment abgeschlossen
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}Deployment erfolgreich abgeschlossen!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "${YELLOW}Hinweise:${NC}"
echo "- Stellen Sie sicher, dass die .env-Datei korrekt konfiguriert ist"
echo "- Prüfen Sie die Berechtigungen für storage/ und bootstrap/cache/"
echo "- Bei Problemen: php artisan config:clear && php artisan cache:clear"
echo ""

