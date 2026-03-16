<?php
session_start();
require_once '../db/conexao.php';

$erro = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_digitado = $_POST['usuario'];
    $senha_digitada   = $_POST['senha'];

    // 1. Busca na tabela 'painel'
    $sql = "SELECT id, usuario, senha FROM painel WHERE usuario = :usuario";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':usuario', $usuario_digitado);
    $stmt->execute();

    $dados = $stmt->fetch();

    // 2. Verifica a senha
    if ($dados && password_verify($senha_digitada, $dados['senha'])) {
        
        // Login Correto! Salva na sessão.
        $_SESSION['id'] = $dados['id'];
        $_SESSION['usuario'] = $dados['usuario'];

        header("Location: painel.php");
        exit;

    } else {
        $erro = "Usuário ou senha incorretos.";
    }
}
?>
<!DOCTYPE html>
<html class="light" lang="pt-BR"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Estágio Fortaleza - Login</title>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#ff0000",
                        "background-light": "#fafaf9",
                        "card-light": "#ffffff",
                        "text-main": "#111718",
                        "text-muted": "#64748b",
                    },
                    fontFamily: {
                        "display": ["Manrope", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "2xl": "1rem",
                        "full": "9999px"
                    },
                    boxShadow: {
                        "soft": "0 4px 20px -2px rgba(0, 0, 0, 0.05)",
                    }
                },
            },
        }
    </script>
<style type="text/tailwindcss">
        @layer utilities {
            .material-symbols-outlined {
                font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            }
        }
    </style>
</head>
<body class="bg-background-light font-display text-text-main antialiased selection:bg-primary selection:text-white flex flex-col min-h-screen">
<main class="flex-grow flex items-center justify-center px-4 py-12">
<div class="w-full max-w-md">
<div class="bg-card-light rounded-2xl shadow-soft border border-gray-100 p-8 md:p-10">
<div class="flex flex-col items-center mb-8">
<div class="flex items-center gap-2 mb-6">
<div class="size-10 bg-primary rounded-lg flex items-center justify-center text-white">
<img alt="Logo Estágio Fortaleza" class="w-6 h-6" src="../img/logo.jpg"/>
</div>
<a href="../index.php"><span class="text-xl font-extrabold tracking-tight">Estágio Fortaleza</span></a>
</div>
<h2 class="text-2xl font-bold text-gray-900">Área restrita</h2>
</div>
<form class="space-y-5" method="post">
<div>
<label class="block text-sm font-semibold text-gray-700 mb-1.5" for="email">Login:</label>
<div class="relative group">
<input class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none text-gray-900 placeholder-gray-400" id="email" name="usuario" type="text" required/>
</div>
</div>
<div>
<div class="flex justify-between items-center mb-1.5">
<label class="block text-sm font-semibold text-gray-700" for="password">Senha:</label>
</div>
<div class="relative group">
<input class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none text-gray-900 placeholder-gray-400" id="password" name="senha" type="password" required/>
<button class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors" type="button">
</button>
<?php if($erro): ?>
        <p style="color: red;"><?php echo $erro; ?></p><?php endif; ?>
</div>
</div>
<div class="pt-2">
<button class="w-full py-4 bg-primary hover:bg-red-700 text-white font-bold rounded-xl shadow-lg shadow-red-500/20 transition-all transform active:scale-[0.98] flex items-center justify-center gap-2" type="submit">
                        Entrar
                    </button>
</div>
</form>
</div>

</div>
</main>
<footer class="py-6 text-center text-xs text-text-muted">
<div class="max-w-7xl mx-auto px-4">
<p>© 2026 - 2025 Estágio Fortaleza. Todos os direitos reservados.</p>
</div>
</footer>

</body></html>