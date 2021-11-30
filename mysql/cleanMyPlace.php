<?php

	//connect
    require_once('../mysql/mysql_connect_rw.php');	
	require_once('../mysql/load_session.php');
	
	if(!isset($_SESSION["session_user"]))
	{
		// User invalid session
		$conn->close();
		die();
	}
	$_session_user = $_SESSION["session_user"];
	$_session_username = $_SESSION["session_username"];

	// Check if it's a real session
	if($_session_user != crypt($_session_username, $_session_username.$_session_username))
	{
		// User invalid session
		$conn->close();
		die();
	}

	// Delete all previous data
	$query = "delete from `MARKET` where `cle_user`=(select `cle` from `ALL_USERS` where `pseudo`='".$_session_username."')";
	$result = $conn->query($query);

    //disconnect
    $conn->close();
    die();
?>
