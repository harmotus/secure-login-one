<?php
	session_start();
	if ( !isset($_POST['usercode'], $_SESSION['hashcode'], $_SESSION['timeus']) ) {
		exit(header('Location: login.php'));
	}
	if ( time() - $_SESSION['timeus'] > 120 ) {
		exit('<br> Alert Message ! <br><br> The access code has expired ( 120 seconds )');
	} else {
		$hashs = strval($_POST['usercode']);
		$hasha = preg_replace('/[^0-9]/', '', $hashs);
		$hashu = hash('sha256', $hasha);
		$hashc = $_SESSION['hashcode'];
	}
	if ( $hashu === $hashc ) {
		session_regenerate_id();
		$_SESSION['connected'] = TRUE;
		$_SESSION['username'] = $_SESSION['username'];
		$_SESSION['id'] = $_SESSION['id'];
		header('Location: home.php');
	} else {
		echo '<br> Alert Message ! <br><br> The code is not correct ( private keys : <a href="keys/keys.html">keys/keys.html</a>)';
	}
?>
