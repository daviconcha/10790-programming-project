<?php
require_once 'db/config.php';

// Verificar se o ID do link foi fornecido
if (!isset($_GET['link_id'])) {
    die("Link ID not provided.");
}

$linkId = intval($_GET['link_id']);

// Obter os detalhes dos visitantes do banco de dados
$stmt = $conn->prepare("SELECT ip_address, language, user_agent, referer, country, visited_at 
                        FROM link_visits WHERE link_id = :linkId");
$stmt->execute(['linkId' => $linkId]);
$visitors = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Nome do arquivo Excel
$fileName = "visitors_" . date('Y-m-d') . ".xls";

// Configurar cabeçalhos para download
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$fileName\"");

// Gerar o conteúdo do Excel
echo implode("\t", ['IP Address', 'Language', 'User Agent', 'Referer', 'Country', 'Visited At']) . "\n";

if (!empty($visitors)) {
    foreach ($visitors as $row) {
        // Substituir valores nulos por "N/A"
        $row = array_map(function ($value) {
            return $value ?: 'N/A';
        }, $row);
        echo implode("\t", array_values($row)) . "\n";
    }
} else {
    echo "No records found.";
}

exit;
?>
