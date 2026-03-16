<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../db/conexao.php';

try {
    $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 9;
    if ($limit <= 0) $limit = 9;
    if ($offset < 0) $offset = 0;

    // Ordenação: 'recent' (padrão) ou 'most_viewed'
    $order = isset($_GET['order']) ? $_GET['order'] : 'recent';
    if ($order === 'most_viewed') {
        $order_sql = 'cliques DESC, id DESC';
    } else {
        $order_sql = 'id DESC';
    }

    $sql = "SELECT id, titulo, modelo, link, cliques, dia FROM vagas_info ORDER BY {$order_sql} LIMIT :limit OFFSET :offset";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count = count($rows);
    $html = '';

    foreach ($rows as $row) {
        $id = $row['id'];
        $titulo = htmlspecialchars($row['titulo']);
        $modelo = htmlspecialchars($row['modelo']);
        $link = htmlspecialchars($row['link']);
        $cliques = htmlspecialchars($row['cliques']);

        $data_postagem = new DateTime($row['dia']);
        $hoje = new DateTime();
        $data_postagem->setTime(0, 0, 0);
        $hoje->setTime(0, 0, 0);
        $diferenca = $hoje->diff($data_postagem);
        $dias = $diferenca->days;

        if ($dias == 0) {
            $dia = "Hoje";
        } elseif ($dias == 1) {
            $dia = "Ontem";
        } elseif ($dias == 2) {
            $dia = "2 dias atrás";
        } else {
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
            $nome_site = "infojobs";
        } else {
            $nome_site = "site";
        }

        if (stripos($modelo, "Presencial") !== false) {
        $icone_modelo = "apartment";
        } else {
        $icone_modelo = "computer";
        }

        $html .= "<article class='bg-card-light dark:bg-card-dark p-5 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-lg hover:border-primary/30 transition-all group flex flex-col h-full relative overflow-hidden'>";
        $html .= "<a href='redirect.php?id={$id}' target='_blank' rel='nofollow noopener noreferrer'><div class='flex justify-between items-start mb-4'>";
        $html .= "<div class='flex items-center gap-3'>";
        $html .= "<img class='size-12 rounded-lg object-cover bg-gray-50' src='img/{$nome_site}.jpg' alt='Logo exemplo'/>";
        $html .= "<div><h4 class='font-bold text-gray-900 dark:text-white group-hover:text-primary transition-colors text-lg leading-tight'>{$titulo}</h4></div>";
        $html .= "</div></div>";

        $html .= "<div class='flex flex-wrap gap-2 mb-6'>";
        $html .= "<span class='inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-gray-100 dark:bg-gray-800 text-xs font-medium text-gray-600 dark:text-gray-300'>";
        $html .= "<span class='material-symbols-outlined text-[14px]'>location_on</span>Fortaleza, CE</span>";

        $html .= "<span class='inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-green-50 dark:bg-green-900/30 text-xs font-medium text-green-600 dark:text-green-300'>";
        $html .= "<span class='material-symbols-outlined text-[14px]'>{$icone_modelo}</span> {$modelo}</span>";

        $html .= "<span class='inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-gray-100 dark:bg-gray-800 text-xs font-medium text-gray-600 dark:text-gray-300'> 💼 Estágio</span></br>";

        $html .= "<svg xmlns='http://www.w3.org/2000/svg' height='29px' viewBox='0 -960 960 960' width='22px' fill='#e3e3e3'><path d='M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z'></path></svg>";
        $html .= "<span>{$cliques}</span></div>";

        $html .= "<div class='mt-auto flex items-center justify-between pt-4 border-t border-gray-50 dark:border-gray-700/50'>";
        $html .= "<span class='text-xs text-gray-400 flex items-center gap-1'><span class='material-symbols-outlined text-[14px]'>schedule</span> {$dia}</span>";
        $html .= "<a href='redirect.php?id={$id}' target='_blank' rel='nofollow noopener noreferrer' class='text-sm font-bold text-primary hover:primary dark:hover:text-teal-400 transition-colors'>Candidatar-se</a>";
        $html .= "</div>";
        $html .= "<div class='absolute left-0 top-0 bottom-0 w-1 bg-primary transform scale-y-0 group-hover:scale-y-100 transition-transform origin-top'></div>";
        $html .= "</article></a>";
    }

    $response = ['html' => $html, 'count' => $count, 'has_more' => ($count === $limit)];
    echo json_encode($response);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
