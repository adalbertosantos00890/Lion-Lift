<?php

require_once __DIR__ . '/_base.php';

$uid = (int) $_SESSION['user_id'];
$erros_nome = [];
$erros_senha = [];
$erro_form_nome = '';
$erro_form_senha = '';
$valor_nome = null;


function buscar_usuario($conn, $uid) {
    $stmt = mysqli_prepare($conn, 'SELECT username, email, password FROM users WHERE id = ? LIMIT 1');
    if (!$stmt || !mysqli_stmt_bind_param($stmt, 'i', $uid) || !mysqli_stmt_execute($stmt)) {
        error_log('[user_system] Erro ao buscar usuário: ' . mysqli_error($conn));
        return false;
    }
    mysqli_stmt_bind_result($stmt, $nome, $email, $hash);
    $achou = mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    return $achou ? ['username' => $nome, 'email' => $email, 'password' => $hash] : null;
}

$usuario = $conn ? buscar_usuario($conn, $uid) : false;

if ($conn && $usuario === null) { 
    header('Location: ../logout.php');
    exit;
}

if ($usuario && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valido()) {
        
        $msg_csrf = 'Sessão expirada. Recarregue a página e tente novamente.';
        if (isset($_POST['alterar_senha'])) $erro_form_senha = $msg_csrf; else $erro_form_nome = $msg_csrf;

    
    } elseif (isset($_POST['salvar_nome'])) {
        $valor_nome = trim($_POST['username'] ?? '');

        if ($valor_nome === '') {
            $erros_nome['username'] = 'Informe o seu nome completo.';
        } elseif (!preg_match('/\S+\s+\S+/u', $valor_nome)) {
            $erros_nome['username'] = 'Informe nome e sobrenome.';
        } elseif (strlen($valor_nome) > 100) {
            $erros_nome['username'] = 'O nome pode ter no máximo 100 caracteres.';
        }

        if (!$erros_nome) {
            $stmt = mysqli_prepare($conn, 'UPDATE users SET username = ? WHERE id = ?');
            if ($stmt && mysqli_stmt_bind_param($stmt, 'si', $valor_nome, $uid) && mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                $_SESSION['username'] = $valor_nome;
                definir_flash('success', 'Nome atualizado com sucesso.');
                header('Location: meus-dados.php');
                exit;
            }
            error_log('[user_system] Erro ao atualizar nome: ' . mysqli_error($conn));
            $erro_form_nome = MSG_INDISPONIVEL;
        }

    
    } elseif (isset($_POST['alterar_senha'])) {
        $atual = $_POST['senha_atual'] ?? '';
        $nova = $_POST['nova_senha'] ?? '';
        $confirma = $_POST['confirmar_senha'] ?? '';

        if ($atual === '') {
            $erros_senha['senha_atual'] = 'Informe a sua senha atual.';
        } elseif (!password_verify($atual, $usuario['password'])) {
            $erros_senha['senha_atual'] = 'A senha atual está incorreta.';
        }

        if ($nova === '') {
            $erros_senha['nova_senha'] = 'Crie uma nova senha.';
        } elseif (strlen($nova) < 8) {
            $erros_senha['nova_senha'] = 'A senha deve ter pelo menos 8 caracteres.';
        } elseif (strlen($nova) > 72) {
            $erros_senha['nova_senha'] = 'A senha pode ter no máximo 72 caracteres.';
        } elseif ($atual === $nova) {
            $erros_senha['nova_senha'] = 'A nova senha deve ser diferente da atual.';
        }

        if ($confirma === '') {
            $erros_senha['confirmar_senha'] = 'Confirme a nova senha.';
        } elseif ($nova !== $confirma) {
            $erros_senha['confirmar_senha'] = 'As senhas não são iguais.';
        }

        if (!$erros_senha) {
            $novo_hash = password_hash($nova, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conn, 'UPDATE users SET password = ? WHERE id = ?');
            if ($stmt && mysqli_stmt_bind_param($stmt, 'si', $novo_hash, $uid) && mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                session_regenerate_id(true);
                definir_flash('success', 'Senha alterada com sucesso.');
                header('Location: meus-dados.php');
                exit;
            }
            error_log('[user_system] Erro ao alterar senha: ' . mysqli_error($conn));
            $erro_form_senha = MSG_INDISPONIVEL;
        }
    }
}

$flash = pegar_flash();
$token = csrf_token();
$nome_no_campo = $valor_nome !== null ? $valor_nome : ($usuario['username'] ?? '');

cliente_abrir('Meus dados', 'dados');
?>
          <span class="eyebrow">Área do Cliente</span>
          <h1>Meus dados</h1>
          <p class="lede">Consulte os dados cadastrados na sua conta, edite o seu nome ou altere a senha.</p>

          <?php if ($flash): ?>
            <p class="form-status is-visible is-<?= e($flash['tipo']) ?>" role="status"><?= e($flash['texto']) ?></p>
          <?php endif; ?>

          <?php if (!$usuario): ?>
            <p class="form-status is-visible is-error" role="alert"><?= e(MSG_INDISPONIVEL) ?></p>
          <?php else: ?>

          <div class="client-card">
            <h2>Dados da conta</h2>

            <form action="meus-dados.php" method="POST" data-validar data-envio-nativo novalidate>
              <input type="hidden" name="csrf" value="<?= e($token) ?>">
              <p class="form-status<?= $erro_form_nome ? ' is-visible is-error' : '' ?>" role="alert"><?= e($erro_form_nome) ?></p>

              <div class="form-row<?= row_class($erros_nome, 'username') ?>">
                <label for="username">Nome completo <span class="req-mark" aria-hidden="true">*</span></label>
                <input type="text" id="username" name="username" value="<?= e($nome_no_campo) ?>" autocomplete="name" required data-validate="fullname" data-msg-required="Informe o seu nome completo."<?= field_attrs($erros_nome, 'username') ?>>
                <?php field_error($erros_nome, 'username'); ?>
              </div>

              <div class="client-field">
                <span class="client-field-label">E-mail</span>
                <p class="client-field-value"><?= e($usuario['email']) ?></p>
                <p class="field-hint">O e-mail é o seu acesso à conta e não pode ser alterado aqui.</p>
              </div>

              <button type="submit" name="salvar_nome" class="btn btn-primary">Salvar nome</button>
            </form>
          </div>

          <div class="client-card">
            <h2>Alterar senha</h2>

            <form action="meus-dados.php" method="POST" data-validar data-envio-nativo novalidate>
              <input type="hidden" name="csrf" value="<?= e($token) ?>">
              <p class="form-status<?= $erro_form_senha ? ' is-visible is-error' : '' ?>" role="alert"><?= e($erro_form_senha) ?></p>

              <div class="form-row<?= row_class($erros_senha, 'senha_atual') ?>">
                <label for="senha_atual">Senha atual <span class="req-mark" aria-hidden="true">*</span></label>
                <input type="password" id="senha_atual" name="senha_atual" autocomplete="current-password" required data-msg-required="Informe a sua senha atual."<?= field_attrs($erros_senha, 'senha_atual') ?>>
                <?php field_error($erros_senha, 'senha_atual'); ?>
              </div>

              <div class="form-row<?= row_class($erros_senha, 'nova_senha') ?>">
                <label for="nova_senha">Nova senha <span class="req-mark" aria-hidden="true">*</span></label>
                <input type="password" id="nova_senha" name="nova_senha" placeholder="Mínimo de 8 caracteres" autocomplete="new-password" maxlength="72" required data-validate="password" data-msg-required="Crie uma nova senha."<?= field_attrs($erros_senha, 'nova_senha') ?>>
                <?php field_error($erros_senha, 'nova_senha'); ?>
              </div>

              <div class="form-row<?= row_class($erros_senha, 'confirmar_senha') ?>">
                <label for="confirmar_senha">Confirmar nova senha <span class="req-mark" aria-hidden="true">*</span></label>
                <input type="password" id="confirmar_senha" name="confirmar_senha" placeholder="Repita a nova senha" autocomplete="new-password" maxlength="72" required data-match="nova_senha" data-msg-match="As senhas não são iguais." data-msg-required="Confirme a nova senha."<?= field_attrs($erros_senha, 'confirmar_senha') ?>>
                <?php field_error($erros_senha, 'confirmar_senha'); ?>
              </div>

              <button type="submit" name="alterar_senha" class="btn btn-primary">Alterar senha</button>
            </form>
          </div>

          <?php endif; ?>
<?php cliente_fechar(); ?>
