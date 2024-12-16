<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JSON Upload</title>
</head>
<body>
    <h1>JSON-Datei hochladen</h1>
    <form action="{{ route('bankdata.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="json_file">Wähle eine JSON-Datei:</label>
        <input type="file" name="json_file" id="json_file" accept=".json" required>
        <button type="submit">Hochladen</button>
    </form>
</body>
</html>
