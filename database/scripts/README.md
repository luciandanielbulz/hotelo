# Bankdaten-Typen automatisch aktualisieren

Dieses Verzeichnis enthält Scripts zur automatischen Aktualisierung der Bankdaten-Typen basierend auf dem Betrag.

## Verfügbare Methoden

### 1. Laravel Migration (bereits ausgeführt)
Die Migration `2025_07_30_141848_update_bankdata_types_based_on_amount.php` wurde bereits ausgeführt und hat alle bestehenden Datensätze aktualisiert.

### 2. Artisan Command
```bash
# Aktualisiere nur Datensätze ohne Typ
php artisan bankdata:update-types

# Aktualisiere alle Datensätze (auch bereits gesetzte)
php artisan bankdata:update-types --force
```

### 3. SQL-Script
Führen Sie das SQL-Script `update_bankdata_types.sql` direkt in Ihrer Datenbank aus:

```sql
-- Aktualisiere alle Datensätze ohne Typ
UPDATE bankdata 
SET type = CASE 
    WHEN amount >= 0 THEN 'income' 
    ELSE 'expense' 
END 
WHERE type IS NULL OR type = '';
```

## Logik

- **Positiver Betrag (>= 0)**: Wird als "income" (Einnahmen) klassifiziert
- **Negativer Betrag (< 0)**: Wird als "expense" (Ausgaben) klassifiziert

## Überprüfung

Nach der Ausführung können Sie die Ergebnisse überprüfen:

```sql
-- Statistiken anzeigen
SELECT 
    COUNT(*) as total_records,
    SUM(CASE WHEN type = 'income' THEN 1 ELSE 0 END) as income_records,
    SUM(CASE WHEN type = 'expense' THEN 1 ELSE 0 END) as expense_records
FROM bankdata;
```

## Sicherheit

- Alle Scripts sind idempotent (können mehrfach ausgeführt werden)
- Die Migration wurde bereits ausgeführt
- Das Artisan-Command prüft vor der Ausführung
- Das SQL-Script aktualisiert nur Datensätze ohne Typ (standardmäßig) 