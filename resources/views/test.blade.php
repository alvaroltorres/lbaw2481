<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test CSRF</title>
</head>
<body>
    <h1>CSRF Token Test</h1>
    <form method="POST" action="/test">
        @csrf
        <input type="text" name="example" placeholder="Enter something">
        <button type="submit">Submit</button>
    </form>
</body>
</html>

