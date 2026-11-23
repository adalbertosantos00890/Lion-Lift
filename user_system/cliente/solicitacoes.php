<?php

require_once __DIR__ . '/_base.php';

$uid = (int) $_SESSION['user_id'];
$erro_banco = false;
$lista = [];

if (!$conn) {
    $erro_banco = true;
} else {
    $sql = 'SELECT id, servico, finalidade, empresa, telefone, email, veiculo_disponivel,
                   marca, modelo, ano, cidade_estado, descricao, created_at
            FROM solicitacoes
            WHERE user_id = ?
            ORDER BY created_at DESC, id DESC';
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt && mysqli_stmt_bind_param($stmt, 'i', $uid) && mysqli_stmt_execute($stmt)) {
        mysqli_stmt_bind_result(
            $stmt, $s_id, $s_servico, $s_finalidade, $s_empresa, $s_telefone, $s_email,
            $s_veiculo, $s_marca, $s_modelo, $s_ano, $s_cidade, $s_descricao, $s_data
        );
        while (mysqli_stmt_fetch($stmt)) {
            $lista[] = [
                'id' => $s_id, 'servico' => $s_servico, 'finalidade' => $s_finalidade,
                'empresa' => $s_empresa, 'telefone' => $s_telefone, 'email' => $s_email,
                'veiculo' => $s_veiculo, 'marca' => $s_marca, 'modelo' => $s_modelo, 'ano' => $s_ano,
                'cidade' => $s_cidade, 'descricao' => $s_descricao, 'data' => $s_data,
            ];
        }
        mysqli_stmt_close($stmt);
    } else {
        $erro_banco = true;
        error_log('[user_system] Erro ao listar solicitações: ' . mysqli_error($conn));
    }
}

cliente_abrir('Minhas solicitações', 'solicitacoes');
?>
          <span class="eyebrow">Área do Cliente</span>
          <h1>Minhas solicitações</h1>
          <p class="lede">Solicitações de orçamento enviadas enquanto você estava conectado à sua conta.</p>

          <?php if ($erro_banco): ?>
            <p class="form-status is-visible is-error" role="alert"><?= e(MSG_INDISPONIVEL) ?></p>

          <?php elseif (!$lista): ?>
            <div class="client-empty">
              <h2>Nenhuma solicitação encontrada</h2>
              <p>Você ainda não enviou nenhuma solicitação de orçamento com esta conta. Quando enviar uma estando conectado, ela aparecerá aqui.</p>
              <a href="../site/orcamento.php" class="btn btn-primary">Solicitar orçamento</a>
            </div>

          <?php else: ?>
            <p class="client-count"><?= count($lista) ?> <?= count($lista) === 1 ? 'solicitação registrada' : 'solicitações registradas' ?></p>
            <ul class="request-list">
              <?php foreach ($lista as $r):
                  $veiculo = $r['veiculo'] === 'sim'
                      ? trim(implode(' ', array_filter([$r['marca'], $r['modelo'], $r['ano']], 'strlen')))
                      : 'Ainda não disponível';
              ?>
              <li class="request-card">
                <div class="request-head">
                  <div>
                    <span class="request-id">Solicitação nº <?= (int) $r['id'] ?></span>
                    <h2><?= e($r['servico']) ?></h2>
                  </div>
                  <div class="request-meta">
                    <time><?= e(formatar_data($r['data'])) ?></time>
                    <span class="request-badge">Enviada e registrada</span>
                  </div>
                </div>

                <dl class="request-details">
                  <div><dt>Finalidade</dt><dd><?= e(rotulo_finalidade($r['finalidade'])) ?></dd></div>
                  <div><dt>Veículo</dt><dd><?= e($veiculo !== '' ? $veiculo : 'Disponível') ?></dd></div>
                  <?php if ($r['cidade'] !== null && $r['cidade'] !== ''): ?>
                    <div><dt>Cidade / estado</dt><dd><?= e($r['cidade']) ?></dd></div>
                  <?php endif; ?>
                  <?php if ($r['empresa'] !== null && $r['empresa'] !== ''): ?>
                    <div><dt>Empresa</dt><dd><?= e($r['empresa']) ?></dd></div>
                  <?php endif; ?>
                  <div><dt>Telefone</dt><dd><?= e($r['telefone']) ?></dd></div>
                  <div><dt>E-mail</dt><dd><?= e($r['email']) ?></dd></div>
                  <?php if ($r['descricao'] !== null && $r['descricao'] !== ''): ?>
                    <div class="request-wide"><dt>Informações adicionais</dt><dd><?= nl2br(e($r['descricao'])) ?></dd></div>
                  <?php endif; ?>
                </dl>
              </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
<?php cliente_fechar(); ?>
