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
            <h1 class="form-title">Criar nova vaga</h1>
            <p class="form-subtitle">Preencha os detalhes para encontrar o candidato ideal.</p>
        </div>
        <details>
  <summary>Opções:</summary>
  <ul>
    <li><a href="vaga_edit.php">Editar Vaga</a></li>
  </ul>
</details>

        <div class="form-card">
            
            <div class="form-row">
                <div class="col form-group">
                    <label for="titulo" class="form-label">Título da Vaga *</label>
                    <input type="text" name="titulo" id="titulo" class="form-control" placeholder="Ex: Desenvolvedor Back-end" required>
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
                <input type="text" name="link" id="link" class="form-control" placeholder="https://..." required>
            </div>

            <div id="resultado"></div>

            <div class="form-actions">
                <button type="button" class="btn btn-cancel" onclick="window.location.href='sair.php'">Sair</button>
                <button type="submit" class="btn btn-submit" id="btnEnviar">Publicar Vaga</button>
            </div>
                </div>
    </main>
    <script>
        // 1. Selecionamos o botão que você escolheu
        const botao = document.getElementById('btnEnviar');

        // 2. Adicionamos o evento de "click" nele
        botao.addEventListener('click', function() {
            
            // --- PEGANDO OS INPUTS ESPECÍFICOS QUE VOCÊ QUER ---
            
            // Busca o input que tem name="titulo" e pega o valor (.value)
            let valorTitulo = document.querySelector('input[name="titulo"]').value;

            let valorModelo   = document.querySelector('select[name="modelo"]').value;
            
            // Busca o input que tem name="link"
            let valorLink   = document.querySelector('input[name="link"]').value;

            // Validação simples no Front (opcional)
            if(valorTitulo === '' || valorLink === '') {
                alert('Por favor, preencha titulo, link e dia.');
                return; // Para o código aqui se estiver vazio
            }

            // --- PREPARANDO O PACOTE PARA ENVIAR ---
            let dadosParaEnviar = new FormData();
            dadosParaEnviar.append('titulo', valorTitulo);
            dadosParaEnviar.append('modelo', valorModelo);
            dadosParaEnviar.append('link', valorLink);

            // --- ENVIANDO VIA AJAX (FETCH) ---
            fetch('../db/enviar_dados.php', {
                method: 'POST',
                body: dadosParaEnviar
            })
            .then(response => response.text()) // Esperamos texto ou html de volta
            .then(resultado => {
                // Exibe o resultado na div
                document.getElementById('resultado').innerHTML = resultado;
                
                // Opcional: Limpar os campos manualmente após sucesso
                document.querySelector('input[name="titulo"]').value = '';
                document.querySelector('input[name="link"]').value = '';
            })
            .catch(erro => {
                console.error('Erro:', erro);
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