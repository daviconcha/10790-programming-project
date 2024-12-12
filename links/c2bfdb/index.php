<?php
        require_once '../../db/config.php';
        
        $linkId = 226;
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        $referer = $_SERVER['HTTP_REFERER'] ?? null;
        
        // Obter o idioma preferido do usuário a partir do cabeçalho Accept-Language
        $acceptedLanguages = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'en';  // Padrão é 'en' se não houver valor
        $language = substr($acceptedLanguages, 0, 2);  // Pegando o código de idioma (primeiros 2 caracteres)
        
        // Obter o país com base no IP (usando uma API ou uma biblioteca de geolocalização)
        // Exemplo fictício de como pode ser feito:
        $country = 'Unknown';  // Substitua por uma lógica real de geolocalização
        
        // Inserir os dados na tabela link_visits
        $stmt = $conn->prepare("INSERT INTO link_visits (link_id, ip_address, user_agent, referer, language, country) 
                                 VALUES (:linkId, :ip, :userAgent, :referer, :language, :country)");
        $stmt->execute([
            ':linkId' => $linkId,
            ':ip' => $ipAddress,
            ':userAgent' => $userAgent,
            ':referer' => $referer,
            ':language' => $language,
            ':country' => $country
        ]);
        
        header('Location: https://pt.scribd.com/document/692343336/Simone-Alessandria-Flutter-Cookbook-100-Step-By-step-Recipes-for-Building-Cross-platform-Professional-grade-Apps-With-Flutter-3-10-x-and-Dart-3-x');
        exit;