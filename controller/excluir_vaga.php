<?php
// Inicia sessão (caso precise verificar se está logado)
session_start();

// Verifica se o usuário está logado (Segurança Básica)
if (!isset($_SESSION['id'])) {
    header("Location: ../restrito/login.php"); // Chuta para login se não tiver permissão
    exit;
}

require_once '../db/conexao.php'; // Ajuste o caminho da sua conexão

// 1. Recebe e Valida o ID
// O filter_input garante que só aceita se for NÚMERO INTEIRO
$id_vaga = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id_vaga) {

    try {
        // 2. Prepara o comando DELETE
        $sql = "DELETE FROM vagas_info WHERE id = :id";
        $stmt = $conn->prepare($sql);

        // 3. Vincula o ID com segurança (PDO)
        $stmt->bindValue(':id', $id_vaga, PDO::PARAM_INT);

        // 4. Executa
        if ($stmt->execute()) {
            
            // Verifica se alguma linha foi realmente apagada
            if ($stmt->rowCount() > 0) {
                // Sucesso: Redireciona de volta para a lista com mensagem
                echo "Vaga excluída com sucesso.";
            } else {
                // O ID era válido, mas não existia no banco
                echo "Nenhuma vaga encontrada com o ID fornecido.";
            }
            
        } else {
            echo "Erro ao tentar excluir.";
        }

    } catch (PDOException $e) {
        echo "Erro no banco de dados: " . $e->getMessage();
    }

} else {
    echo "ID inválido ou não informado.";
}
?>