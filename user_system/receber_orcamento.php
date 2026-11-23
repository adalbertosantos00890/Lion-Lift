<?php

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function responder($http, $dados) {
    http_response_code($http);
    echo json_encode($dados, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    responder(405, ['sucesso' => false, 'mensagem' => 'Método não permitido.']);
}


iniciar_sessao();
$user_id = !empty($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
session_write_close();

function campo($nome) {
    $v = $_POST[$nome] ?? '';
    return is_string($v) ? trim($v) : '';
}

$nome          = campo('nome');
$empresa       = campo('empresa');
$telefone      = campo('telefone');
$email         = campo('email');
$veiculo       = campo('veiculo_disponivel');
$marca         = campo('marca');
$modelo        = campo('modelo');
$ano           = campo('ano');
$cidade_estado = campo('cidade_estado');
$servico       = campo('servico');
$finalidade    = campo('finalidade');
$descricao     = campo('descricao');

$finalidades = [
    'manutencao-redes-eletricas', 'iluminacao-publica', 'telecomunicacoes',
    'poda-manutencao-arvores', 'outra',
];


$erro = '';
$digitos = preg_replace('/\D/', '', $telefone);
if (strlen($digitos) > 11 && strpos($digitos, '55') === 0) $digitos = substr($digitos, 2);

if ($nome === '' || !preg_match('/\S+\s+\S+/u', $nome))      $erro = 'Informe o seu nome completo.';
elseif (mb_strlen($nome) > 100)                               $erro = 'O nome pode ter no máximo 100 caracteres.';
elseif (mb_strlen($empresa) > 150)                            $erro = 'O nome da empresa pode ter no máximo 150 caracteres.';
elseif (strlen($telefone) > 30 || strlen($digitos) < 10 || strlen($digitos) > 11 || $digitos[0] === '0'
        || (strlen($digitos) === 11 && $digitos[2] !== '9'))  $erro = 'Informe um telefone válido com DDD.';
elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 255) $erro = 'Informe um e-mail válido.';
elseif (!in_array($veiculo, ['sim', 'nao'], true))            $erro = 'Informe se o veículo já está disponível.';
elseif ($veiculo === 'sim' && ($marca === '' || $modelo === '' || $ano === ''))
                                                              $erro = 'Informe marca, modelo e ano do veículo.';
elseif (mb_strlen($marca) > 80 || mb_strlen($modelo) > 80)    $erro = 'Marca e modelo podem ter no máximo 80 caracteres.';
elseif ($ano !== '' && (!preg_match('/^\d{4}$/', $ano) || (int) $ano < 1900 || (int) $ano > (int) date('Y') + 1))
                                                              $erro = 'Informe um ano válido com 4 dígitos.';
elseif (mb_strlen($cidade_estado) > 100)                      $erro = 'Cidade e estado podem ter no máximo 100 caracteres.';
elseif ($servico === '')                                      $erro = 'Informe o serviço ou solução desejada.';
elseif (mb_strlen($servico) > 255)                            $erro = 'O serviço pode ter no máximo 255 caracteres.';
elseif (!in_array($finalidade, $finalidades, true))           $erro = 'Selecione a finalidade de utilização.';
elseif (mb_strlen($descricao) > 5000)                         $erro = 'A descrição pode ter no máximo 5000 caracteres.';

if ($erro !== '') {
    responder(422, ['sucesso' => false, 'mensagem' => $erro]);
}

if (!$conn) {
    responder(503, ['sucesso' => false, 'mensagem' => 'Não foi possível enviar agora. Tente novamente em alguns instantes ou fale conosco pelo WhatsApp.']);
}


if ($user_id !== null) {
    $stmt = mysqli_prepare($conn, 'SELECT id FROM users WHERE id = ? LIMIT 1');
    $existe = false;
    if ($stmt && mysqli_stmt_bind_param($stmt, 'i', $user_id) && mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);
        $existe = mysqli_stmt_num_rows($stmt) > 0;
    }
    if ($stmt) mysqli_stmt_close($stmt);
    if (!$existe) $user_id = null;
}


$nulo = function ($v) { return $v === '' ? null : $v; };
$empresa       = $nulo($empresa);
$marca         = $nulo($marca);
$modelo        = $nulo($modelo);
$ano           = $nulo($ano);
$cidade_estado = $nulo($cidade_estado);
$descricao     = $nulo($descricao);

$sql = 'INSERT INTO solicitacoes
          (user_id, nome, empresa, telefone, email, veiculo_disponivel, marca, modelo, ano,
           cidade_estado, servico, finalidade, descricao)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt || !mysqli_stmt_bind_param(
        $stmt, 'issssssssssss',
        $user_id, $nome, $empresa, $telefone, $email, $veiculo, $marca, $modelo, $ano,
        $cidade_estado, $servico, $finalidade, $descricao
    ) || !mysqli_stmt_execute($stmt)) {
    error_log('[user_system] Erro ao gravar solicitação: ' . ($stmt ? mysqli_stmt_error($stmt) : mysqli_error($conn)));
    if ($stmt) mysqli_stmt_close($stmt);
    responder(500, ['sucesso' => false, 'mensagem' => 'Não foi possível enviar agora. Tente novamente em alguns instantes ou fale conosco pelo WhatsApp.']);
}

$id = mysqli_insert_id($conn);
mysqli_stmt_close($stmt);

responder(200, ['sucesso' => true, 'id' => $id]);
