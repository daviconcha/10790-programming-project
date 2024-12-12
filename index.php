<?php
require_once 'db/config.php';

// Busca os últimos 5 links encurtados para exibição (opcional, pode manter ou remover)
$stmt = $conn->query("SELECT * FROM links ORDER BY created_at DESC LIMIT 5");
$latestLinks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>LinkSnap - SEO Best Friend</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/img/favicon.png" type="image/x-icon">
</head>
<body>
    <a href="/linksnap"><h1>Link<img height="48" src="assets/img/broken-link-96.png" alt="">Snap</h1></a>
    <form id="shortenerForm" action="create_link.php" method="post">
        <p>Hit "Snap" to create a quick and shareable short link.</p><br>
        <input type="url" id="url" name="url" placeholder="Paste your URL here (e.g., https://yoursite.com)" required>
        <button type="submit">Snap</button>
    </form>

    <div id="linkContainer" class="link-container">
        <!-- Os links serão exibidos aqui após o envio do formulário -->
    </div>
    <footer>
        <p>© 2024 LinkSnap. All rights reserved.</p>
    </footer>
    <!-- Link para o script.js -->
    <script src="assets/js/script.js"></script>
</body>
</html>
