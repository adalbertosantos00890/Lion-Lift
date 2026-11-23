<?php

require_once __DIR__ . '/../db.php';       
require_once __DIR__ . '/../layout.php';   

iniciar_sessao();
exigir_login('../');                      

const CLIENTE_MENU = [
    'visao-geral'  => ['Visão geral',        'index.php'],
    'solicitacoes' => ['Minhas solicitações', 'solicitacoes.php'],
    'dados'        => ['Meus dados',          'meus-dados.php'],
];

// Primeiro nome para a saudação
function primeiro_nome($nome) {
    $partes = preg_split('/\s+/u', trim((string) $nome));
    return $partes[0] ?? '';
}


function pegar_flash() {
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

function definir_flash($tipo, $texto) {
    $_SESSION['flash'] = ['tipo' => $tipo, 'texto' => $texto];
}


function cliente_abrir($titulo, $ativo) {
    layout_header($titulo, '../');
    ?>

  <main>
    <section class="section client-section">
      <div class="container client-layout">

        <aside class="client-sidebar" aria-label="Menu da área do cliente">
          <div class="client-sidebar-user">
            <span class="client-avatar" aria-hidden="true"><?= e(mb_strtoupper(mb_substr(trim((string) ($_SESSION['username'] ?? '?')), 0, 1))) ?></span>
            <div>
              <span class="client-sidebar-label">Área do Cliente</span>
              <strong class="client-sidebar-name"><?= e($_SESSION['username'] ?? '') ?></strong>
            </div>
          </div>

          <nav class="client-menu">
            <ul>
              <?php foreach (CLIENTE_MENU as $chave => [$rotulo, $arquivo]): ?>
                <li><a href="<?= e($arquivo) ?>"<?= $chave === $ativo ? ' aria-current="page"' : '' ?>><?= e($rotulo) ?></a></li>
              <?php endforeach; ?>
            </ul>
            <ul class="client-menu-secondary">
              <li><a href="../site/index.php">Voltar ao site</a></li>
              <li><a href="../logout.php">Sair da conta</a></li>
            </ul>
          </nav>
        </aside>

        <div class="client-content">
<?php
}

function cliente_fechar() {
    ?>
        </div>
      </div>
    </section>
  </main>

<?php
    layout_footer('../');
}


function rotulo_finalidade($valor) {
    $mapa = [
        'manutencao-redes-eletricas' => 'Manutenção de redes elétricas',
        'iluminacao-publica'         => 'Iluminação pública',
        'telecomunicacoes'           => 'Telecomunicações',
        'poda-manutencao-arvores'    => 'Poda e manutenção de árvores',
        'outra'                      => 'Outra finalidade',
    ];
    return $mapa[$valor] ?? $valor;
}

function formatar_data($datahora) {
    $t = strtotime((string) $datahora);
    return $t ? date('d/m/Y \à\s H:i', $t) : '';
}
