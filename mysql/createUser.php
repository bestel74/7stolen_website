<?php
	require_once '../mysql/recaptchalib.php';
	require_once('../mysql/load_session.php');

	// html form : 6LdVKEIdAAAAALdEZTywm0GDZc9tMFa4ZDl5KQc5
	require_once('../mysql/secret_captcha.php');

	// FOR TEST ONLY
	//$secret = "6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe";

	// empty response
	$response = null;

	// check secret key
	$reCaptcha = new ReCaptcha($secret);

	// if submitted check response
	if ($_POST["g-recaptcha-response"]) {
		$response = $reCaptcha->verifyResponse(
			$_SERVER["REMOTE_ADDR"],
			$_POST["g-recaptcha-response"]
		);
	}
	
	// Check the pseudo is free 
	require_once('../mysql/mysql_connect_ro.php');
	

	$pseudo = $conn->real_escape_string($_POST['pseudo']);
	$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
	$email = $conn->real_escape_string($_POST['email']);

	$query = "select * from `ALL_USERS` where `pseudo`='".$pseudo."' or `email`='".$email."'";
	$result = $conn->query($query);
	$conn->close();

	if ($response != null && $response->success && ($result->num_rows == 0 || $email=='')) {
		echo "Hey " . $_POST["pseudo"] . " (" . $_POST["email"] . "), ton compte vient d'être crée !";
		$_SESSION["session_user"] = crypt($pseudo, $pseudo.$pseudo);
		$_SESSION["session_username"] = $_POST["pseudo"];

		require_once('../mysql/mysql_connect_rw.php');

		$query = "INSERT INTO `ALL_USERS` (`pseudo`, `password`, `email`) VALUES ('".$pseudo."','".$password."','".$email."')";
		$result = $conn->query($query);
		$conn->close();
	}
	
	header('Location: ../myplace/');
	die();
?>
