<?php

require_once __DIR__ . '/db.php';       
require_once __DIR__ . '/layout.php';   

session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
session_start(); 

$email = '';
$errors = [];       
$form_error = '';   

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // 1) Validação dos campos
    if ($email === '') {
        $errors['email'] = 'Informe o seu e-mail.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Informe um e-mail válido, como nome@exemplo.com.';
    }
    if ($password === '') {
        $errors['password'] = 'Informe a sua senha.';
    }

    
    if (!$errors) {
        if (!$conn) {
            $form_error = MSG_INDISPONIVEL;
        } else {
            $stmt = mysqli_prepare($conn, 'SELECT id, username, password FROM users WHERE email = ? LIMIT 1');

            if ($stmt && mysqli_stmt_bind_param($stmt, 's', $email) && mysqli_stmt_execute($stmt)) {
                mysqli_stmt_bind_result($stmt, $user_id, $username, $password_hash);
                $found = mysqli_stmt_fetch($stmt);
                mysqli_stmt_close($stmt);

                
                if ($found && password_verify($password, $password_hash)) {
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['username'] = $username;

                    
                    header('Location: site/index.php?aviso=login');
                    exit;
                }

                
                $form_error = 'E-mail ou senha incorretos. Confira os dados e tente novamente.';
            } else {
                error_log('[user_system] Erro no login: ' . mysqli_error($conn));
                $form_error = MSG_INDISPONIVEL;
            }
        }
    }
}

layout_header('Entrar');
?>

  <main>
    <section class="section auth-section">
      <div class="container">
        <div class="form-panel auth-panel">
          <span class="eyebrow">Área do Cliente</span>
          <h1>Entrar</h1>
          <p class="lede">Acesse a sua conta com e-mail e senha.</p>

          <form action="login.php" method="POST" data-validar data-envio-nativo novalidate>
            <p class="form-status<?= $form_error ? ' is-visible is-error' : '' ?>" role="alert"><?= e($form_error) ?></p>

            <div class="form-row<?= row_class($errors, 'email') ?>">
              <label for="email">E-mail <span class="req-mark" aria-hidden="true">*</span></label>
              <input type="email" id="email" name="email" value="<?= e($email) ?>" placeholder="seuemail@exemplo.com" autocomplete="email" required data-validate="email" data-msg-required="Informe o seu e-mail."<?= field_attrs($errors, 'email') ?>>
              <?php field_error($errors, 'email'); ?>
            </div>

            <div class="form-row<?= row_class($errors, 'password') ?>">
              <label for="password">Senha <span class="req-mark" aria-hidden="true">*</span></label>
              <input type="password" id="password" name="password" placeholder="Sua senha" autocomplete="current-password" required data-msg-required="Informe a sua senha."<?= field_attrs($errors, 'password') ?>>
              <?php field_error($errors, 'password'); ?>
            </div>

            <button type="submit" name="login" class="btn btn-primary">Entrar</button>
          </form>

          <p class="auth-switch">Ainda não tem uma conta? <a href="register.php" class="text-link">Criar uma conta</a></p>
        </div>
      </div>
    </section>
  </main>

<?php layout_footer(); ?>
