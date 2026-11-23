<?php

require_once __DIR__ . '/auth.php';
header('Location: ' . (usuario_logado() ? 'cliente/index.php' : 'login.php'));
exit;
