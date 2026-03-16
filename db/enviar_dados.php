<?php
// 1. Sempre inicie a sessão antes de tudo
session_start();

// Define que a resposta será um JSON (para o JavaScript entender)
header('Content-Type: application/json');

$response = [];

// 2. VERIFICAÇÃO DE SEGURANÇA (SESSÃO)
// Substitua 'usuario_logado' pelo nome real da sua variável de sessão
if (!isset($_SESSION['id'])) {
    http_response_code(403); // Status HTTP de "Proibido"
    $response['status'] = 'erro';
    $response['mensagem'] = 'Acesso negado. Você precisa estar logado para criar uma vaga.';
    echo json_encode($response);
    exit; // Para a execução do script aqui para não inserir nada
}

// Só carrega a conexão se passou pela verificação acima
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Pega os dados enviados pelo FormData do JS
        $titulo = $_POST['titulo'] ?? '';
        $modelo = $_POST['modelo'] ?? '';
        $link = $_POST['link'] ?? '';

        if (!empty($titulo) && !empty($link)) {
            
            $sql = "INSERT INTO vagas_info (titulo, modelo, link) VALUES (:titulo, :modelo, :link)";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':titulo', $titulo);
            $stmt->bindValue(':modelo', $modelo);
            $stmt->bindValue(':link', $link);
            
            if ($stmt->execute()) {
                // Sucesso
                $response['status'] = 'sucesso'; // É bom padronizar também o status de sucesso
                $response['mensagem'] = "Vaga '{$titulo}' salva com sucesso!";
            } else {
                // Erro na execução
                $response['status'] = 'erro';
                $response['mensagem'] = 'Falha ao salvar no banco.';
            }

        } else {
            $response['status'] = 'erro';
            $response['mensagem'] = 'Preencha todos os campos obrigatórios (Título e Link).';
        }

    } catch (PDOException $e) {
        $response['status'] = 'erro';
        // Em produção, evite mostrar o erro técnico completo para o usuário
        $response['mensagem'] = "Erro técnico: {$e->getMessage()}";
    }
}

// Devolve a resposta em formato JSON para o JavaScript
echo json_encode($response);
?>