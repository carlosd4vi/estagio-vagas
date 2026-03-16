<?php

// Inicia sessão (caso precise verificar se está logado)
session_start();

// Verifica se o usuário está logado (Segurança Básica)
if (!isset($_SESSION['id'])) {
    header("Location: ../restrito/login.php"); // Chuta para login se não tiver permissão
    exit;
}

require_once '../db/conexao.php';

// Verifica se veio via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Recebe o ID e os dados
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $titulo = $_POST['titulo'];
    $modelo = $_POST['modelo'];
    $link = $_POST['link'];

    if ($id && !empty($titulo)) {
        
        // SQL DE ATUALIZAÇÃO
        $sql = "UPDATE vagas_info 
                SET titulo = :titulo, modelo = :modelo, link = :link 
                WHERE id = :id";
        
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':titulo', $titulo);
            $stmt->bindValue(':modelo', $modelo);
            $stmt->bindValue(':link', $link);
            $stmt->bindValue(':id', $id);
            
            if ($stmt->execute()) {
                echo "Vaga atualizada com sucesso!";
            } else {
                echo "Erro ao atualizar.";
            }
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }

    } else {
        echo "ID ou Título faltando.";
    }
}
?>