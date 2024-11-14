<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Angebot</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .content {
            margin: 20px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Angebot ID: {{ $offer_id }}</h1>
        <p>Typ: {{ $objecttype }}</p>
    </div>
    <div class="content">
        <p>Dies ist ein Testangebot mit der ID {{ $offer_id }}.</p>
        <p>Zusätzliche Daten können hier angezeigt werden.</p>
    </div>
    <div class="footer">
        <p>© 2024 Dein Unternehmen</p>
    </div>
</body>
</html>
