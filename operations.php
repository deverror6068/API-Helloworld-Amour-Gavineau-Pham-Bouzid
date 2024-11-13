<?php

// Récupération des paramètres
$operation = $_GET['operation'] ?? null;
$num1 = isset($_GET['num1']) ? (int)$_GET['num1'] : null;
$num2 = isset($_GET['num2']) ? (int)$_GET['num2'] : null;

// Validation des entrées
if ($operation === null || $num1 === null || $num2 === null) {
    http_response_code(400);
    $response = ['error' => "Paramètres manquants ou invalides. Utilisez ?operation=[add|sust|multiplication|divide]&num1=[int]&num2=[int]"];
    sendResponse($response, "application/json");
    exit;
}

// Calcul de l'opération
$result = null;
switch ($operation) {
    case 'add':
        $result = $num1 + $num2;
        break;
    case 'sust':
        $result = $num1 - $num2;
        break;
    case 'multiplication':
        $result = $num1 * $num2;
        break;
    case 'divide':
        $result = $num1 / $num2;
        if ($result === 0) {
            http_response_code(400);
            $response = ["error" => "Division par zéro impossible."];
            sendResponse($response, "application/json");
            exit;
        }
        break;
    default:
        http_response_code(400);
        $response = ["error" => "Opération invalide. Utilisez add, sust, multiplication ou divide."];
        sendResponse($response, "application/json");
        exit;
}

// Détection du type de contenu préféré
$acceptedContentTypes = $_SERVER["HTTP_ACCEPT"] ?? 'application/json';
if (strpos($acceptedContentTypes, 'application/json') !== false || strpos($acceptedContentTypes, '*/*') !== false) {
    sendResponse(["result" => $result], "application/json");
} elseif (strpos($acceptedContentTypes, 'text/plain') !== false) {
    sendResponse($result, "text/plain");
} else {
    http_response_code(406); // 406 Not Acceptable
    sendResponse(["error" => "Type de contenu non supporté. Utilisez application/json ou text/plain."], "application/json");
}


// Fonction d'envoi de réponse
function sendResponse($response, $contentType)
{
    header("Content-Type: $contentType");
    if ($contentType === "application/json") {
        echo json_encode($response);
    } else {
        echo $response;
    }
}
