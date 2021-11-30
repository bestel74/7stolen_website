<?php

	if(empty($_POST))
	{
		die();
	}

	//connect
    require_once('../mysql/mysql_connect_rw.php');

	$cle = $conn->real_escape_string($_POST['cle']);
    $nbr = $conn->real_escape_string($_POST['nbr']); 
    $info = $conn->real_escape_string($_POST['info']);
	
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

	// Create new data
    $query = "insert into `MARKET` (`cle_user`,`cle_card`,`nbr`,`info`) values ((select `cle` from `ALL_USERS` where `pseudo`='".$_session_username."'),'".$cle."','".$nbr."','".$info."')";
	$result = $conn->query($query);

    //disconnect
    $conn->close();
    die();
?>
