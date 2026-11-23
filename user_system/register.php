<?php

require_once __DIR__ . '/db.php';       
require_once __DIR__ . '/layout.php';   

session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
session_start();

$username = '';
$email = '';
$errors = [];       
$form_error = '';   

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    
    if ($username === '') {
        $errors['username'] = 'Informe o seu nome completo.';
    } elseif (!preg_match('/\S+\s+\S+/u', $username)) {
        $errors['username'] = 'Informe nome e sobrenome.';
    } elseif (strlen($username) > 100) {
        $errors['username'] = 'O nome pode ter no máximo 100 caracteres.';
    }

    if ($email === '') {
        $errors['email'] = 'Informe o seu e-mail.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 255) {
        $errors['email'] = 'Informe um e-mail válido, como nome@exemplo.com.';
    }

    if ($password === '') {
        $errors['password'] = 'Crie uma senha.';
    } elseif (strlen($password) < 8) {
        $errors['password'] = 'A senha deve ter pelo menos 8 caracteres.';
    } elseif (strlen($password) > 72) {
        $errors['password'] = 'A senha pode ter no máximo 72 caracteres.';
    }

    if ($confirm_password === '') {
        $errors['confirm_password'] = 'Confirme a sua senha.';
    } elseif ($password !== $confirm_password) {
        $errors['confirm_password'] = 'As senhas não são iguais.';
    }

    
    if (!$errors) {
        if (!$conn) {
            $form_error = MSG_INDISPONIVEL;
        } else {
            $result = cadastrar_usuario($conn, $username, $email, $password);

            if ($result['status'] === 'ok') {
                
                session_regenerate_id(true);
                $_SESSION['user_id'] = $result['id'];
                $_SESSION['username'] = $username;
                header('Location: site/index.php?aviso=cadastro');
                exit;
            } elseif ($result['status'] === 'duplicado') {
                $errors['email'] = 'Este e-mail já está cadastrado. Tente fazer login.';
            } else {
                $form_error = MSG_INDISPONIVEL;
            }
        }
    }
}

function cadastrar_usuario($conn, $username, $email, $password) {
   
    $stmt = mysqli_prepare($conn, 'SELECT id FROM users WHERE email = ? LIMIT 1');
    if (!$stmt || !mysqli_stmt_bind_param($stmt, 's', $email) || !mysqli_stmt_execute($stmt)) {
        error_log('[user_system] Erro ao verificar e-mail: ' . mysqli_error($conn));
        return ['status' => 'erro'];
    }
    mysqli_stmt_store_result($stmt);
    $ja_existe = mysqli_stmt_num_rows($stmt) > 0;
    mysqli_stmt_close($stmt);

    if ($ja_existe) {
        return ['status' => 'duplicado'];
    }

    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = mysqli_prepare($conn, 'INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
    if (!$stmt || !mysqli_stmt_bind_param($stmt, 'sss', $username, $email, $hashed_password)) {
        error_log('[user_system] Erro ao preparar o cadastro: ' . mysqli_error($conn));
        return ['status' => 'erro'];
    }

    if (!mysqli_stmt_execute($stmt)) {
        $codigo = mysqli_stmt_errno($stmt);
        error_log('[user_system] Erro ao cadastrar (' . $codigo . '): ' . mysqli_stmt_error($stmt));
        mysqli_stmt_close($stmt);
        
        return ['status' => ($codigo === 1062) ? 'duplicado' : 'erro'];
    }

    $id = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);
    return ['status' => 'ok', 'id' => $id];
}

layout_header('Criar conta');
?>

  <main>
    <section class="section auth-section">
      <div class="container">
        <div class="form-panel auth-panel">
          <span class="eyebrow">Área do Cliente</span>
          <h1>Criar uma conta</h1>
          <p class="lede">Preencha os dados abaixo para criar a sua conta.</p>

          <form action="register.php" method="POST" data-validar data-envio-nativo novalidate>
            <p class="form-status<?= $form_error ? ' is-visible is-error' : '' ?>" role="alert"><?= e($form_error) ?></p>

            <div class="form-row<?= row_class($errors, 'username') ?>">
              <label for="username">Nome completo <span class="req-mark" aria-hidden="true">*</span></label>
              <input type="text" id="username" name="username" value="<?= e($username) ?>" placeholder="Seu nome completo" autocomplete="name" required data-validate="fullname" data-msg-required="Informe o seu nome completo."<?= field_attrs($errors, 'username') ?>>
              <?php field_error($errors, 'username'); ?>
            </div>

            <div class="form-row<?= row_class($errors, 'email') ?>">
              <label for="email">E-mail <span class="req-mark" aria-hidden="true">*</span></label>
              <input type="email" id="email" name="email" value="<?= e($email) ?>" placeholder="seuemail@exemplo.com" autocomplete="email" required data-validate="email" data-msg-required="Informe o seu e-mail."<?= field_attrs($errors, 'email') ?>>
              <?php field_error($errors, 'email'); ?>
            </div>

            <div class="form-row<?= row_class($errors, 'password') ?>">
              <label for="password">Senha <span class="req-mark" aria-hidden="true">*</span></label>
              <input type="password" id="password" name="password" placeholder="Mínimo de 8 caracteres" autocomplete="new-password" maxlength="72" required data-validate="password" data-msg-required="Crie uma senha."<?= field_attrs($errors, 'password') ?>>
              <?php field_error($errors, 'password'); ?>
            </div>

            <div class="form-row<?= row_class($errors, 'confirm_password') ?>">
              <label for="confirm_password">Confirmar senha <span class="req-mark" aria-hidden="true">*</span></label>
              <input type="password" id="confirm_password" name="confirm_password" placeholder="Repita a senha" autocomplete="new-password" maxlength="72" required data-match="password" data-msg-match="As senhas não são iguais." data-msg-required="Confirme a sua senha."<?= field_attrs($errors, 'confirm_password') ?>>
              <?php field_error($errors, 'confirm_password'); ?>
            </div>

            <button type="submit" name="register" class="btn btn-primary">Criar conta</button>
          </form>

          <p class="auth-switch">Já tem uma conta? <a href="login.php" class="text-link">Voltar para o login</a></p>
        </div>
      </div>
    </section>
  </main>

<?php layout_footer(); ?>
