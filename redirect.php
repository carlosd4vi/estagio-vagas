<?php
// conectar.php deve conter sua conexão PDO ou mysqli
require 'db/conexao.php';
// 1. Pega o ID da URL
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if ($id) {
    // 2. Busca a URL de destino antes de incrementar (ou junto)
    $sql_busca = "SELECT link FROM vagas_info WHERE id = :id LIMIT 1";
    $stmt_busca = $conn->prepare($sql_busca);
    $stmt_busca->execute(['id' => $id]);
    $vaga = $stmt_busca->fetch(PDO::FETCH_ASSOC);

    if ($vaga) {
        // 3. Incrementa o contador de cliques
        $sql_update = "UPDATE vagas_info SET cliques = cliques + 1 WHERE id = :id";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->execute(['id' => $id]);

        // 4. Redireciona para a URL vinda do banco de dados
        header("Location: " . $vaga['link']);
        exit;
    }
}

// Caso o ID não exista ou não seja passado, redireciona para a home
header("Location: index.php");
exit;

?>