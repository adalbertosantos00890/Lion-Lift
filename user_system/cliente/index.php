<?php

require_once __DIR__ . '/_base.php';

$uid = (int) $_SESSION['user_id'];
$erro_banco = false;
$usuario = null;
$total = 0;
$ultima = null;

if (!$conn) {
    $erro_banco = true;
} else {
   
    $stmt = mysqli_prepare($conn, 'SELECT username, email FROM users WHERE id = ? LIMIT 1');
    if ($stmt && mysqli_stmt_bind_param($stmt, 'i', $uid) && mysqli_stmt_execute($stmt)) {
        mysqli_stmt_bind_result($stmt, $u_nome, $u_email);
        if (mysqli_stmt_fetch($stmt)) $usuario = ['username' => $u_nome, 'email' => $u_email];
        mysqli_stmt_close($stmt);
    } else {
        $erro_banco = true;
        error_log('[user_system] Erro na visão geral (usuário): ' . mysqli_error($conn));
    }

    
    if ($usuario) {
        $stmt = mysqli_prepare($conn, 'SELECT COUNT(*), MAX(created_at) FROM solicitacoes WHERE user_id = ?');
        if ($stmt && mysqli_stmt_bind_param($stmt, 'i', $uid) && mysqli_stmt_execute($stmt)) {
            mysqli_stmt_bind_result($stmt, $total, $ultima);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);
        } else {
            $erro_banco = true;
            error_log('[user_system] Erro na visão geral (solicitações): ' . mysqli_error($conn));
        }
    }
}


if ($conn && !$erro_banco && !$usuario) {
    header('Location: ../logout.php');
    exit;
}

$_SESSION['username'] = $usuario['username'] ?? $_SESSION['username'];

cliente_abrir('Visão geral', 'visao-geral');
?>
          <span class="eyebrow">Área do Cliente</span>
          <h1>Olá, <?= e(primeiro_nome($_SESSION['username'])) ?>!</h1>
          <p class="lede">Bem-vindo à sua área do cliente da Lion Lift. Aqui você acompanha as solicitações de orçamento enviadas enquanto estava conectado e gerencia os seus dados.</p>

          <?php if ($erro_banco): ?>
            <p class="form-status is-visible is-error" role="alert"><?= e(MSG_INDISPONIVEL) ?></p>
          <?php else: ?>

          <h2 class="client-subtitle">Resumo da conta</h2>
          <dl class="client-summary">
            <div class="client-stat">
              <dt>Nome</dt>
              <dd><?= e($usuario['username']) ?></dd>
            </div>
            <div class="client-stat">
              <dt>E-mail</dt>
              <dd><?= e($usuario['email']) ?></dd>
            </div>
            <div class="client-stat">
              <dt>Solicitações de orçamento</dt>
              <dd class="client-stat-number"><?= (int) $total ?></dd>
              <?php if ($total > 0 && $ultima): ?>
                <span class="client-stat-note">Última em <?= e(formatar_data($ultima)) ?></span>
              <?php endif; ?>
            </div>
          </dl>

          <h2 class="client-subtitle">Atalhos</h2>
          <div class="client-shortcuts">
            <a class="client-shortcut" href="solicitacoes.php">
              <strong>Minhas solicitações</strong>
              <span>Veja os orçamentos que você enviou.</span>
              <em class="card-action">Abrir →</em>
            </a>
            <a class="client-shortcut" href="meus-dados.php">
              <strong>Meus dados</strong>
              <span>Edite o seu nome e altere a senha.</span>
              <em class="card-action">Abrir →</em>
            </a>
            <a class="client-shortcut" href="../site/orcamento.php">
              <strong>Solicitar orçamento</strong>
              <span>Envie uma nova solicitação, já vinculada à sua conta.</span>
              <em class="card-action">Abrir →</em>
            </a>
          </div>

          <?php endif; ?>
<?php cliente_fechar(); ?>
