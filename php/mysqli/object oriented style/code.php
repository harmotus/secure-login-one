<?php
	session_start();
	if ( !isset($_POST['username']) ) {
		exit(header('Location: login.php'));
	}
	$users = strval($_POST['username']);
	$userx = preg_replace('/[^a-zA-Z0-9]/', '', $users);
	$userc = strtolower($userx);
	$servername = 'localhost';
	$usernamesv = 'root';
	$passwordsv = '';
	$dbnamedbsv = 'slogin';
	$conn = new mysqli($servername, $usernamesv, $passwordsv, $dbnamedbsv);
	if ( $conn->connect_errno ) {
		exit('<br> Alert Message ! <br><br> Failed to connect to MySQL (' . $conn->connect_errno . ') ' . $conn->connect_error);
	}
	if ( $stmt = $conn->prepare('SELECT id, pubkey FROM accounts WHERE username = ?') ) {
		$stmt->bind_param('s', $userc);
		$stmt->execute();
		$stmt->store_result();
		if ( $stmt->num_rows > 0 ) {
			$stmt->bind_result($id, $pubkey);
			$stmt->fetch();
			$stmt->close();
			$conn->close();
			// RANDOM CODE
			$puckey = "-----BEGIN PUBLIC KEY-----\r\n" . chunk_split($pubkey) . "-----END PUBLIC KEY-----";
			$randnc = strval( mt_rand(100000,999999) . mt_rand(100000,999999) );
			openssl_public_encrypt($randnc, $datanc, $puckey);
			$hashrc = hash('sha256', $randnc);
			$randnc = 0;
			unset($randnc);
			// TIMEOUT DATA
			$timeux = time();
			// SESSION DATA
			$_SESSION['username'] = $userc;
			$_SESSION['hashcode'] = $hashrc;
			$_SESSION['timeus'] = $timeux;
			$_SESSION['id'] = $id;
		} else {
			$stmt->close();
			$conn->close();
			exit('<br> Alert Message ! <br><br> The username is not correct ( user : alex or anna )');
		}
	} else {
		$conn->close();
		exit(header('Location: login.php'));
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="author" content="Harmotus">
	<meta name="robots" content="noindex, nofollow">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/style.css">
	<link rel="icon" href="images/favicon.svg">
	<title>Code</title>
</head>
<body>
	<div class="cod"><h1>Access Code</h1></div>
	<div class="alx">
		<div class="ima" id="qrcodejs"></div>
		<div class="box vox">
			<h1>Code</h1>
			<form action="test.php" method="post">
				<div class="bax">
					<label for="usercode" title="lock-icon">
						<img class="ico" src="images/lock.svg" alt="lock-icon">
					</label>
					<input id="usercode" type="text" name="usercode" placeholder="Code ( 12-digit )" pattern="[0-9]{12}" autocomplete="off" minlength="12" maxlength="12" required>
					<!--
					<input id="usercode" type="text" name="usercode" placeholder="Code ( 12-digit )" pattern="([0-9]{1}[._+ \-]{0,1}){12}" autocomplete="off" minlength="12" maxlength="24" required>
					-->
				</div>
				<div class="nox"></div>
				<input type="submit" value="Submit">
			</form>
		</div>
	</div>
	<div class="alc" id="txtcodec" onclick="myDisplay();">Display <span class="nux">&nbsp;the&nbsp;</span> <span class="nux">encrypted&nbsp;</span> code<span class="nex">&nbsp;in plain text ( Base64 encoded )</span></div>
	<div class="aln" id="txtcoden"><span class="kpb"><?php echo base64_encode($datanc); ?></span></div>

	<!--
	<div class="aln" id="txtcoden"><?php // echo '<span class="kpb">' . wordwrap(base64_encode($datanc), 1, '</span><span class="kpb">', true) . '</span>'; ?></div>
	-->
	<!--
	<div class="aln" id="txtcoden">function wordwrap . javascript from the user</div>
	-->

	<script src="qrcode/qrcode.min.js"></script>

	<script>
		var QRCodeDiv = document.getElementById("qrcodejs");
		var QRCodeNew = new QRCode(QRCodeDiv, {
			text: '<?php echo base64_encode($datanc); ?>',
			width: 272,
			height: 272,
			colorDark : "#000000",
			colorLight : "#ffffff",
			correctLevel : QRCode.CorrectLevel.L
		});
		document.getElementById("qrcodejs").title="qr-code";
	</script>

	<!--

	<script src="js/wordwrap.js"></script>

	<script>
		document.getElementById("txtcoden").innerHTML = wordwrap (
			str = '<?php // echo base64_encode($datanc); ?>',
			ope = '<span class="kpb">',
			int = '</span><span class="kpb">',
			clo = '</span>',
			brk = 1
		);
	</script>

	-->

	<script>
		function myDisplay() {
				document.getElementById("txtcodec").style.display="none";
				document.getElementById("txtcoden").style.display="flex";
		};
	</script>

	<script>
		setTimeout(function(){document.write('<br> Alert Message ! <br><br> The access code has expired ( 120 seconds )');document.close();}, 120000);
	</script>

</body>
</html>
