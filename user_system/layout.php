<?php


require_once __DIR__ . '/auth.php';   

const SITE_DIR = 'site/';
const MSG_INDISPONIVEL = 'Não foi possível concluir agora. Tente novamente em alguns instantes.';


function row_class($errors, $campo) {
    return isset($errors[$campo]) ? ' is-invalid' : '';
}


function field_attrs($errors, $campo) {
    return isset($errors[$campo]) ? ' aria-invalid="true" aria-describedby="erro-' . e($campo) . '"' : '';
}


function field_error($errors, $campo) {
    if (isset($errors[$campo])) {
        echo '<p class="field-error" id="erro-' . e($campo) . '">' . e($errors[$campo]) . '</p>';
    }
}

function layout_header($titulo, $raiz = '') {
    $s = $raiz . SITE_DIR;
    ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($titulo) ?> — Lion Lift | Cestas Aéreas</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= $s ?>style.css">
  <script src="<?= $s ?>script.js" defer></script>
</head>
<body>

  
  <header class="site-header">
    <div class="container">
      <a href="<?= $s ?>index.php" class="brand" aria-label="Lion Lift — página inicial">
        <img src="<?= $s ?>imagens/Logo.png" alt="" class="brand-logo">
        <span class="brand-name">Lion <span>Lift</span></span>
      </a>

      <nav class="nav" id="site-nav">
        <ul class="nav-links">
          <li><a href="<?= $s ?>index.php">Início</a></li>
          <li><a href="<?= $s ?>cesta-aerea.php">Cesta Aérea</a></li>
          <li><a href="<?= $s ?>faq.php">FAQ</a></li>
          <li><a href="<?= $s ?>localizacao.php">Localização</a></li>
          <li><a href="<?= $s ?>contato.php">Contato</a></li>
        </ul>
        <a href="<?= $s ?>orcamento.php" class="btn btn-primary nav-cta">Solicitar orçamento</a>
<?php render_conta_nav($raiz); ?>
      </nav>

      <button class="nav-toggle" aria-label="Abrir menu" aria-expanded="false" aria-controls="site-nav">
        <span></span>
      </button>
    </div>
  </header>
<?php
}

function layout_footer($raiz = '') {
    $s = $raiz . SITE_DIR;
    $maps = 'https://www.google.com/maps/search/?api=1&amp;query=Rua+Virg%C3%ADnia+Pratta+Gregolin%2C+575%2C+Piracicaba%2C+SP%2C+Brasil';
    ?>

  
  <footer class="site-footer">
    <div class="container footer-grid">
      <div class="footer-brand">
        <div class="footer-brand-row">
          <img src="<?= $s ?>imagens/Logo.png" alt="" class="footer-logo">
          <span class="brand-name">Lion <span>Lift</span></span>
        </div>
        <p class="footer-tagline">Fabricação e instalação de cestas aéreas.</p>
      </div>
      <div class="footer-contact">
        <span>Telefone: <span class="placeholder-value">(19) 99943-4699</span></span>
        <span>E-mail: <span class="placeholder-value">juniorcesar.clemente@gmail.com</span></span>
        <span>Endereço: <a class="placeholder-value" href="<?= $maps ?>" target="_blank" rel="noopener noreferrer">Rua Virgínia Pratta Gregolin, 575
Piracicaba, SP</a></span>
      </div>
    </div>
  </footer>

</body>
</html>
<?php
}
