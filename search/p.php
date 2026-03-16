<!DOCTYPE html>
<html class="light" lang="pt-BR"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Estágio Fortaleza</title>
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
                        "background-dark": "#22262a",
                        "card-light": "#ffffff",
                        "card-dark": "#2c3035",
                        "text-main": "#111718",
                        "text-muted": "#638588",
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
                        "glow": "0 0 15px rgba(18, 149, 161, 0.3)",
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
            .icon-filled {
                font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            }
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-text-main dark:text-gray-100 antialiased selection:bg-primary selection:text-white flex flex-col min-h-screen transition-colors duration-300">
<header class="sticky top-0 z-50 bg-white/90 dark:bg-background-dark/95 backdrop-blur-md border-b border-[#f0f4f4] dark:border-gray-800 transition-colors duration-300">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="flex items-center justify-between h-16">
<div class="flex items-center gap-2 group">
<div class="rounded-lg flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors">
</div>
<a href="../index.php"><img class="size-8 bg-primary/10 rounded-lg flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors" src="../img/logo.jpg" alt="Logo Estágio Fortaleza" class="h-8 w-auto"/></a>
<h1 class="text-[#111718] dark:text-white text-xl font-bold tracking-tight">Estágio Fortaleza</h1>
</div> </a>
<div class="flex items-center gap-4">
<!-- <button class="flex items-center justify-center rounded-lg h-10 px-6 bg-primary hover:bg-primary/80 text-white text-sm font-bold shadow-lg shadow-teal-500/20 transition-all transform active:scale-95">
                        Entrar
                    </button> -->
</div>
</div>
</div>
</header>
<main class="flex-grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
<section class="flex flex-col items-center justify-center mb-16 text-center space-y-8">
<div class="w-full max-w-2xl">
<form class="flex flex-row items-center bg-white dark:bg-card-dark rounded-2xl shadow-soft dark:shadow-none border border-gray-100 dark:border-gray-700 p-2 gap-2 transition-all hover:shadow-lg focus-within:shadow-xl focus-within:ring-1 focus-within:ring-primary/20" action="p.php" method="get">
<div class="flex-1 flex items-center px-4 relative group/input">
<span class="material-symbols-outlined text-gray-400 group-focus-within/input:text-primary transition-colors">search</span>
<div class="ml-3 flex-1">
<input class="w-full bg-transparent border-none p-0 text-base font-medium text-gray-900 dark:text-white placeholder-gray-300 focus:ring-0 leading-tight" id="keyword" name="cargo" placeholder="Digite seu cargo..." type="text" required/>
</div>
</div>
<button class="h-14 w-14 md:w-32 rounded-xl bg-primary hover:bg-primary/80 text-white font-bold text-lg flex items-center justify-center gap-2 shadow-lg shadow-teal-500/20 transition-all transform active:scale-[0.98]" type="submit">
<span class="material-symbols-outlined md:hidden">search</span>
<span class="hidden md:inline">Buscar</span>
</button>
</form>
</div>
</section>
<div class="w-full space-y-8">
<div class="flex items-center justify-between mb-6">
<h3 class="text-xl font-bold text-gray-900 dark:text-white">Resultados encontrados:</h3>
</div>

<script>
history.pushState(null, null, location.href);
window.onpopstate = function () {
    // Força ir para a home quando clicar em voltar
    window.location.href = "../index.php"; 
};
</script>

<!-- card de vaga: -->

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

  <!-- Modelo de card (copie/cole o bloco <article>...</article> para duplicar) -->

  <?php
require_once '../db/conexao.php';
// Captura o termo digitado
$termo_busca = isset($_GET['cargo']) ? trim($_GET['cargo']) : '';

if (!empty($termo_busca)) {

    try {
        // 1. Preparamos o termo com as porcentagens PARA O PHP
        // Isso transforma "rh" em "%rh%"
        $parametro_busca = "%" . $termo_busca . "%";

        // 2. Mudamos o SQL: usamos LIKE em vez de =
        // O LIKE por padrão no MySQL já ignora maiúsculas/minúsculas (case-insensitive)
        $sql = "SELECT id, titulo, modelo, link, cliques, dia 
                FROM vagas_info 
                WHERE titulo LIKE :cargo ORDER BY id DESC LIMIT 9";
        
        $stmt = $conn->prepare($sql);
        
        // 3. Vinculamos a variável que já tem as porcentagens
        $stmt->bindValue(':cargo', $parametro_busca, PDO::PARAM_STR);
        
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                $id = $row['id'];
            $titulo = htmlspecialchars($row['titulo']);
            $modelo = htmlspecialchars($row['modelo']);  
            $dia = date('d/m/Y', strtotime($row['dia']));
            $link = htmlspecialchars($row['link']);
            $cliques = htmlspecialchars($row['cliques']);
                    $data_postagem = new DateTime($row['dia']);
        $hoje = new DateTime();

        // 2. Zera as horas para a comparação ser exata (apenas dias)
        $data_postagem->setTime(0, 0, 0);
        $hoje->setTime(0, 0, 0);

        // 3. Calcula a diferença
        $diferenca = $hoje->diff($data_postagem);
        $dias = $diferenca->days;

        // 4. Define o texto da variável $dia
        if ($dias == 0) {
            $dia = "Hoje";
        } elseif ($dias == 1) {
            $dia = "Ontem";
        } elseif ($dias == 2) {
            $dia = "2 dias atrás";
        } else {
            // AQUI ESTÁ O QUE VOCÊ PEDIU:
            // Se passou de 2 dias, mostra a data normal: 29/12/2025
            $dia = $data_postagem->format('d/m/Y');
        }

if (stripos($link, 'indeed.com') !== false) {
    $nome_site = "indeed";
    
} elseif (stripos($link, 'gupy.io') !== false) {
    $nome_site = "gupy";
    
} elseif (stripos($link, 'linkedin.com') !== false) {
    $nome_site = "linkedin";
    
} elseif (stripos($link, 'catho.com.br') !== false) {
    $nome_site = "catho";
    
} elseif (stripos($link, 'infojobs.com.br') !== false) {
    $nome_site = "infojobs"; // Adicionei este pois é comum
    
} else {
    // Caso não seja nenhum dos conhecidos, pega o domínio "cru"
    $nome_site = "site";
}

if (stripos($modelo, "Presencial") !== false) {
    $icone_modelo = "apartment";
    } else {
    $icone_modelo = "computer";
}

               echo "
            <article class='bg-card-light dark:bg-card-dark p-5 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-lg hover:border-primary/30 transition-all group flex flex-col h-full relative overflow-hidden'>
    <a href='redirect.php?id={$id}' target='_blank' rel='nofollow noopener noreferrer'><div class='flex justify-between items-start mb-4'>
      <div class='flex items-center gap-3'>
        <img class='size-12 rounded-lg object-cover bg-gray-50' src='../img/{$nome_site}.jpg' alt='Logo exemplo'/>
        <div>
          <h4 class='font-bold text-gray-900 dark:text-white group-hover:text-primary transition-colors text-lg leading-tight'>{$titulo}</h4>
        </div>
      </div>
    </div>

    <div class='flex flex-wrap gap-2 mb-6'>
      <span class='inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-gray-100 dark:bg-gray-800 text-xs font-medium text-gray-600 dark:text-gray-300'>
        <span class='material-symbols-outlined text-[14px]'>location_on</span>
        Fortaleza, CE
      </span>

      <span class='inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-green-50 dark:bg-green-900/30 text-xs font-medium text-green-600 dark:text-green-300'>
        <span class='material-symbols-outlined text-[14px]'>{$icone_modelo}</span>
        {$modelo}
      </span>

      <span class='inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-gray-100 dark:bg-gray-800 text-xs font-medium text-gray-600 dark:text-gray-300'>
        <span class='material-symbols-outlined text-[14px]'></span>
        💼 Estágio
      </span>
      </br>
      <svg xmlns='http://www.w3.org/2000/svg' height='29px' viewBox='0 -960 960 960' width='22px' fill='#e3e3e3'><path d='M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z'></path></svg>
      <span>{$cliques}</span>
    </div>

    <div class='mt-auto flex items-center justify-between pt-4 border-t border-gray-50 dark:border-gray-700/50'>
      <span class='text-xs text-gray-400 flex items-center gap-1'>
        <span class='material-symbols-outlined text-[14px]'>schedule</span>
        {$dia}
      </span>
      <a href='redirect.php?id={$id}' target='_blank' rel='nofollow noopener noreferrer' class='text-sm font-bold text-primary hover:primary dark:hover:text-teal-400 transition-colors'>Detalhes</a>
    </div>

    <div class='absolute left-0 top-0 bottom-0 w-1 bg-primary transform scale-y-0 group-hover:scale-y-100 transition-transform origin-top'></div>
  </article> </a>
            "; // Uma linha para separar se vier mais de um resultado
            }
        } else {
            echo "Nenhuma vaga encontrada para: " . htmlspecialchars($termo_busca);
            echo "</br>";
        }

    } catch(PDOException $e) {
        echo "Erro na consulta: " . $e->getMessage();
    }

} else {
    echo "Nenhum termo pesquisado.";
    echo "</br>";
}

$conn = null;
?>

</div>
</div>
</main>
<footer class="bg-white dark:bg-card-dark border-t border-gray-200 dark:border-gray-800 py-8 mt-12">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4">
<p class="text-sm text-gray-500 dark:text-gray-400">© 2026 - 2025 Estágio Fortaleza. Todos os direitos reservados.</p>
<div class="flex gap-6">
<a class="text-gray-400 hover:text-primary transition-colors" href="https://www.instagram.com/estagiofortaleza/" target="_blank" rel="nofollow noopener noreferrer">
  <span class="sr-only">Instagram</span>
<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
  <rect x="3" y="3" width="18" height="18" rx="5"></rect>
  <circle cx="12" cy="12" r="3.5"></circle>
  <path d="M17.5 6.5h.01"></path>
</svg>
</a>
</div>
</div>
</footer>

</body></html>