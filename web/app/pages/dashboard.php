<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($title); ?> - <?php echo htmlspecialchars($community); ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($community); ?> Dashboard</h1>
        <p>Players Online: <?php echo htmlspecialchars($players); ?></p>
        <p>Warnings: <?php echo htmlspecialchars($stats['warns']); ?></p>
        <p>Kicks: <?php echo htmlspecialchars($stats['kicks']); ?></p>
        <p>Bans: <?php echo htmlspecialchars($stats['bans']); ?></p>
        <p>Playtime: <?php echo htmlspecialchars($stats['playtime']); ?> minutes</p>
        <!-- Add more dashboard elements -->
    </div>
</body>
</html>