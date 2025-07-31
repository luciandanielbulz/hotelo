-- Script zur automatischen Aktualisierung der Bankdaten-Typen
-- Basierend auf dem Betrag: positiv = Einnahmen, negativ = Ausgaben

-- 1. Aktualisiere alle Datensätze ohne Typ oder mit leerem Typ
UPDATE bankdata 
SET type = CASE 
    WHEN amount >= 0 THEN 'income' 
    ELSE 'expense' 
END 
WHERE type IS NULL OR type = '';

-- 2. Optional: Aktualisiere alle Datensätze (auch bereits gesetzte)
-- Entfernen Sie die Kommentarzeichen, wenn Sie alle Datensätze aktualisieren möchten
-- UPDATE bankdata 
-- SET type = CASE 
--     WHEN amount >= 0 THEN 'income' 
--     ELSE 'expense' 
-- END;

-- 3. Zeige Statistiken
SELECT 
    COUNT(*) as total_records,
    SUM(CASE WHEN type = 'income' THEN 1 ELSE 0 END) as income_records,
    SUM(CASE WHEN type = 'expense' THEN 1 ELSE 0 END) as expense_records,
    SUM(CASE WHEN type IS NULL OR type = '' THEN 1 ELSE 0 END) as records_without_type
FROM bankdata;

-- 4. Zeige Beispiele der Aktualisierung
SELECT 
    id,
    date,
    partnername,
    amount,
    type,
    CASE 
        WHEN amount >= 0 THEN 'income' 
        ELSE 'expense' 
    END as calculated_type
FROM bankdata 
ORDER BY date DESC 
LIMIT 10; 