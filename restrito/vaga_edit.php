<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

// Se chegou aqui, está logado.
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel</title>
    <link rel="stylesheet" href="../estilos/style-painel.css">
</head>
<body>

    <header>
        <div class="header-content">
            <a href="../index.php" class="logo" target="_blank">Estágio Fortaleza</a>
            <div class="user-menu">
                <span><?php echo $_SESSION['usuario']; ?></span>
                <div class="avatar">CD</div>
            </div>
        </div>
    </header>

    <main>
        <div class="form-header">
            <h1 class="form-title">Editar vaga</h1>
            <form action="vaga_edit.php" method="GET">
                     <div class="col form-group">
                    <label for="empresa" class="form-label">ID da Vaga:</label>
                    <input type="text" id="buscar" class="form-control" name="buscar" placeholder="Digite o ID da vaga" required>
                </div>
                 <button type="submit" class="btn btn-submit">Buscar</button>
</form>
        </div>
    <?php
// Supondo que a URL seja: detalhes.php?id=5

// 1. Validação de Segurança
// O filter_input garante que o valor recebido seja um número INTEIRO.
// Se o usuário digitar texto ou algo malicioso, retorna false/null.

require_once '../db/conexao.php';

$id_vaga = filter_input(INPUT_GET, 'buscar', FILTER_VALIDATE_INT);

if ($id_vaga) {

    try {
        // 2. A Query SQL
        // Usamos LIMIT 1 para otimizar, pois ID é único
        $sql = "SELECT id, titulo, modelo, link, cliques, dia 
                FROM vagas_info 
                WHERE id = :id_vaga LIMIT 1";

        $stmt = $conn->prepare($sql);
        
        // 3. Vinculação Segura (PDO::PARAM_INT para números)
        $stmt->bindValue(':id_vaga', $id_vaga, PDO::PARAM_INT);
        $stmt->execute();

        // 4. Verificar se encontrou
        if ($stmt->rowCount() > 0) {
            
            // Usamos fetch() normal, sem while, pois esperamos apenas 1 registro
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Variáveis para facilitar o uso no HTML
            $id  = htmlspecialchars($row['id']);
            $titulo  = htmlspecialchars($row['titulo']);
            $modelo  = htmlspecialchars($row['modelo']);
            $link    = htmlspecialchars($row['link']);
            $cliques = $row['cliques']; // Números não precisam necessariamente de htmlspecialchars
            
            // Formatando a data (usando o padrão brasileiro simples)
            $data_formatada = date('d/m/Y', strtotime($row['dia']));
            ?>  
        <div class="form-card">
            <div class="form-row">
                <div class="col form-group">
                    <label for="titulo" class="form-label">Título da Vaga *</label>
                    <input type="text" value="<?php echo $titulo; ?>" name="titulo" id="titulo" class="form-control" placeholder="Ex: Desenvolvedor Back-end" required>
                </div>
                <div class="col form-group">
                    <label for="empresa" class="form-label">Nome da Empresa *</label>
                    <input type="text" id="empresa" class="form-control" placeholder="Ex: TechSolutions" disabled>
                </div>
            </div>

            <div class="form-row">
                <div class="col form-group">
                    <label for="local" class="form-label">Localização *</label>
                    <input type="text" id="local" class="form-control" placeholder="Ex: São Paulo, SP" disabled>
                </div>
                <div class="col form-group">
                    <label for="modelo" class="form-label">Modelo de Trabalho</label>
                    <select id="modelo" name="modelo" class="form-control">
                        <option value="<?php echo $modelo; ?>">Valor Atual: <?php echo $modelo; ?></option>
                        <option value="Presencial">Presencial</option>
                        <option value="Hibrido">Híbrido</option>
                        <option value="Home Office">Home Office</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="col form-group">
                    <label for="nivel" class="form-label">Nível de Experiência (desativado)</label>
                    <select id="nivel" class="form-control" disabled>
                        <option value="junior">Junior</option>
                        <option value="pleno">Pleno</option>
                        <option value="senior">Sênior</option>
                        <option value="especialista">Especialista</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="tags" class="form-label">Tags / Competências (separadas por vírgula)</label>
                <input type="text" id="tags" class="form-control" placeholder="Ex: React, Java, Inglês Avançado, Figma" disabled>
                <p class="form-helper">Isso ajudará a destacar sua vaga nos cartões de visualização.</p>
            </div>

            <div class="form-group">
                <label for="descricao" class="form-label">Descrição da Vaga *</label>
                <textarea id="descricao" style="display:none;" class="form-control" placeholder="Descreva as responsabilidades, requisitos e benefícios da vaga..."></textarea>
            </div>

            <div class="form-group">
                <label for="link" class="form-label">Link da Vaga *</label>
                <input type="text" value="<?php echo $link; ?>" name="link" id="link" class="form-control" placeholder="https://..." required>
                <input type="hidden" value="<?php echo $id; ?>" id="id_vaga">
            </div>

            <div id="resultado"></div>

            <div class="form-actions">
                <button type="button" class="btn btn-cancel" onclick="window.location.href='sair.php'">Sair</button>
                <button onclick="Excluir()" value="<?php echo $id; ?>" class="btn btn-delete" id="btnDelete">Excluir Vaga</button>
                <button type="submit" class="btn btn-submit" id="btnEnviar">Editar Vaga</button>
            </div>
                </div>
            <?php
        } else {
            echo "<p>Vaga não encontrada ou foi removida.</p>";
        }

    } catch(PDOException $e) {
        echo "Erro no sistema: " . $e->getMessage();
    }

} else {
    echo "<p>ID inválido ou não informado.</p>";
}
?>              
    </main>
    <script>
    const btnDelete = document.getElementById('btnDelete').value;
    function Excluir() {
let temCerteza = confirm("Você tem certeza que deseja excluir este item?");
if (temCerteza) {
  window.location.href = '../controller/excluir_vaga.php?id=' + btnDelete;
} else {
  alert("Exclusão cancelada.");
}}
        </script>
    <script>
        // 1. Selecionamos o botão que você escolheu
    const botao = document.getElementById('btnEnviar');

        // 2. Adicionamos o evento de "click" nele
    botao.addEventListener('click', function() {
    
    // Supondo que você tenha um campo hidden com o ID do registro que está editando
    // <input type="hidden" id="id_vaga" value="15">
    let idVaga = document.getElementById('id_vaga').value; 

    let valorTitulo = document.querySelector('input[name="titulo"]').value;
    let valorModelo = document.querySelector('select[name="modelo"]').value;
    let valorLink   = document.querySelector('input[name="link"]').value;

    let dadosParaEnviar = new FormData();
    
    // O SEGREDO: Adicionamos o ID aqui
    dadosParaEnviar.append('id', idVaga); 
    
    dadosParaEnviar.append('titulo', valorTitulo);
    dadosParaEnviar.append('modelo', valorModelo);
    dadosParaEnviar.append('link', valorLink);

    fetch('../controller/atualizar_vaga.php', { // Crie um arquivo específico ou use lógica no mesmo
        method: 'POST', // MANTENHA POST
        body: dadosParaEnviar
    })
    .then(response => response.text())
    .then(resultado => {
        document.getElementById('resultado').innerHTML = resultado;
    });
});
    </script>
    <footer>
        <div class="copyright">
            &copy; 2025 Estágio Fortaleza. Painel do Recrutador.
        </div>
    </footer>

</body>
</html>