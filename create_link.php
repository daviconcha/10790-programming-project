<?php
require_once 'db/config.php';

header('Content-Type: application/json'); // Resposta em JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = $_POST['url'] ?? '';

    if (filter_var($url, FILTER_VALIDATE_URL)) {
        // Geração de um novo shortCode
        do {
            $shortCode = substr(md5(uniqid(rand(), true)), 0, 6);
            
            // Verifica se o shortCode já existe no banco de dados
            $stmt = $conn->prepare("SELECT COUNT(*) FROM links WHERE short_code = :shortCode");
            $stmt->execute(['shortCode' => $shortCode]);
            $exists = $stmt->fetchColumn();
        } while ($exists); // Repete a geração do shortCode até ser único

        // Insere o link e shortCode no banco de dados
        $stmt = $conn->prepare("INSERT INTO links (original_url, short_code) VALUES (:url, :shortCode)");
        $stmt->execute(['url' => $url, 'shortCode' => $shortCode]);

        // Geração de pastas e arquivos de redirecionamento
        $directory = __DIR__ . "/links/$shortCode";
        mkdir($directory, 0777, true);

        $redirectFile = $directory . "/index.php";
        $redirectContent = "<?php
        require_once '../../db/config.php';
        
        \$linkId = {$conn->lastInsertId()};
        \$ipAddress = \$_SERVER['REMOTE_ADDR'];
        \$userAgent = \$_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        \$referer = \$_SERVER['HTTP_REFERER'] ?? null;
        
        // Obter o idioma preferido do usuário a partir do cabeçalho Accept-Language
        \$acceptedLanguages = \$_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'en';  // Padrão é 'en' se não houver valor
        \$language = substr(\$acceptedLanguages, 0, 2);  // Pegando o código de idioma (primeiros 2 caracteres)
        
        // Obter o país com base no IP (usando uma API ou uma biblioteca de geolocalização)
        // Exemplo fictício de como pode ser feito:
        \$country = 'Unknown';  // Substitua por uma lógica real de geolocalização
        
        // Inserir os dados na tabela link_visits
        \$stmt = \$conn->prepare(\"INSERT INTO link_visits (link_id, ip_address, user_agent, referer, language, country) 
                                 VALUES (:linkId, :ip, :userAgent, :referer, :language, :country)\");
        \$stmt->execute([
            ':linkId' => \$linkId,
            ':ip' => \$ipAddress,
            ':userAgent' => \$userAgent,
            ':referer' => \$referer,
            ':language' => \$language,
            ':country' => \$country
        ]);
        
        header('Location: {$url}');
        exit;";
        


        file_put_contents($redirectFile, $redirectContent);

        // Retorna os links gerados
        echo json_encode([
            "success" => true,
            "shortLink" => "links/$shortCode",
            "statusLink" => "links.php?shortcode=$shortCode"
        ]);
        exit;
    } else {
        echo json_encode(["success" => false, "message" => "URL inválido."]);
        exit;
    }
}
?>
