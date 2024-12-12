<?php
require_once 'db/config.php';

$shortCode = $_GET['shortcode'] ?? null;

if ($shortCode) {
    // Query the link based on the short code
    $stmt = $conn->prepare("SELECT * FROM links WHERE short_code = :shortCode");
    $stmt->execute(['shortCode' => $shortCode]);
    $link = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($link) {
        // Get the number of visits
        $countStmt = $conn->prepare("SELECT COUNT(*) FROM link_visits WHERE link_id = :linkId");
        $countStmt->execute(['linkId' => $link['id']]);
        $visitCount = $countStmt->fetchColumn();  // Get the number of visits

        // Replace zero visits with a friendly message
        if ($visitCount == 0) {
            $visitCount = 'No visitors yet';
        }

        // Fetch visit details
        $visitStmt = $conn->prepare("SELECT * FROM link_visits WHERE link_id = :linkId ORDER BY visited_at DESC");
        $visitStmt->execute(['linkId' => $link['id']]);
        $visits = $visitStmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo "Link not found.";
        exit;
    }
} else {
    echo "Link code not provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Link Details</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/img/favicon.png" type="image/x-icon">
</head>
<body>
    <div class="container">
        <h1>Link<img width="48" height="48" src="assets/img/link_details.png"/>Details</h1>

        <a href="index.php" class="home-btn">Home</a>
        <!-- Div container for link data, with white background -->
        <div class="link-details-box">
            <div class="link-details">
                <p><strong>Original</strong> <?php echo $link['original_url']; ?></p>
                <p><strong>Shortened</strong> <a href="links/<?php echo $link['short_code']; ?>" target="_blank"><?php echo $link['short_code']; ?></a></p>
                <p><strong>Date</strong> <?php echo $link['created_at']; ?></p>
                <p><strong>Clicks</strong> <?php echo $visitCount; ?></p>
            </div>
        </div>
        
        <div class="table-container">
    <table>
        <thead>
            <tr>
                <th colspan="6" class="table-header">
                    <h3>Visitors</h3>
                    <a href="export_visitors.php?link_id=<?php echo $link['id']; ?>" class="export-btn" title="Export to Excel">
                        <img src="assets/img/xls.png" alt="Export" width="24" height="24">
                    </a>
                </th>
            </tr>
            <tr>
                <th>IP</th>
                <th>Language</th>
                <th>User Agent</th>
                <th>Referer</th>
                <th>Country</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($visits)): ?>
                <tr>
                    <td colspan="6">No data to analyze yet.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($visits as $visit): ?>
                    <tr>
                        <td><?php echo $visit['ip_address']; ?></td>
                        <td><?php echo $visit['language']; ?></td>
                        <td><?php echo $visit['user_agent']; ?></td>
                        <td><?php echo $visit['referer'] ?: 'N/A'; ?></td>
                        <td><?php echo $visit['country'] ?: 'N/A'; ?></td>
                        <td><?php echo $visit['visited_at']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
    </div>
    <footer>
        <p>© 2024 LinkSnap. All rights reserved.</p>
    </footer>
</body>
</html>
