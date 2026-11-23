<?php
	
	$host = 'localhost';
	$user = 'root';
	$password = '';
	$dbname = 'user_system'; // Database Name

	
	mysqli_report(MYSQLI_REPORT_OFF);

	$conn = @mysqli_connect($host, $user, $password, $dbname);

	if ($conn) {
		mysqli_set_charset($conn, 'utf8mb4');
	} else {
		
		error_log('[user_system] Falha na conexão com o banco de dados: ' . mysqli_connect_error());
		$conn = false;
	}
?>
