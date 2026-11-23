<?php

if (!function_exists('e')) {
    function e($valor) {
        return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
    }
}


function iniciar_sessao() {
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params(['path' => '/', 'httponly' => true, 'samesite' => 'Lax']);
        session_start();
    }
}

function usuario_logado() {
    iniciar_sessao();
    return !empty($_SESSION['user_id']);
}


function exigir_login($raiz = '') {
    if (!usuario_logado()) {
        header('Location: ' . $raiz . 'login.php');
        exit;
    }
}


function csrf_token() {
    iniciar_sessao();
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_valido() {
    iniciar_sessao();
    $enviado = $_POST['csrf'] ?? '';
    return !empty($_SESSION['csrf']) && is_string($enviado) && hash_equals($_SESSION['csrf'], $enviado);
}


function render_conta_nav($raiz = '') {
    $logado = usuario_logado();
    if (!$logado) {
        ?>
        <div class="nav-account-access">
          <a href="<?= e($raiz) ?>login.php" class="nav-account-link">Fazer login</a>
          <a href="<?= e($raiz) ?>register.php" class="nav-account-link">Criar conta</a>
        </div>
<?php
        return;
    }
    ?>
        <div class="nav-account-access nav-account-access--user">
          <details class="nav-account-menu">
            <summary class="nav-account-link nav-account-toggle">
              Minha conta
              <svg class="nav-account-arrow" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 8l5 5 5-5"/></svg>
            </summary>
            <ul class="nav-account-dropdown">
              <li><a href="<?= e($raiz) ?>cliente/index.php">Visão geral</a></li>
              <li><a href="<?= e($raiz) ?>cliente/solicitacoes.php">Minhas solicitações</a></li>
              <li><a href="<?= e($raiz) ?>cliente/meus-dados.php">Meus dados</a></li>
              <li class="nav-account-sep"><a href="<?= e($raiz) ?>logout.php">Sair da conta</a></li>
            </ul>
          </details>
        </div>
<?php
}
