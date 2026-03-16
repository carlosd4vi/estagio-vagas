<?php
$host = '';
$dbname = '';
$username = '';
$password = '';

try {
    // Cria a conexão PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // Configura para lançar exceções em caso de erro (bom para debug)
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Opcional: Configura o modo de fetch padrão para array associativo
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Nota: Não colocamos "echo 'Conectado!'" aqui, senão essa mensagem 
    // vai aparecer em todas as páginas do seu site.

} catch(PDOException $e) {
    // Se der erro na conexão, para tudo e avisa
    die("Erro grave na conexão: " . $e->getMessage());
}
?>
