<?php
	// Check password
	require_once('../mysql/mysql_connect_ro.php');
	require_once('../mysql/load_session.php');
	
	$pseudo = $conn->real_escape_string($_POST['pseudo']);
	$password = $_POST['password'];

	$query = "select password from `ALL_USERS` where `pseudo`='".$pseudo."'";
	$result = $conn->query($query);

	if($result->num_rows > 0)
	{
		$count=0;
		// output data of each row
		while($row = mysqli_fetch_array($result))
        { 
			$password_bdd = $row['password'];
			
			if(password_verify($password, $password_bdd) == true)
			{
				$_SESSION["session_user"] = crypt($pseudo, $pseudo.$pseudo);
				$_SESSION["session_username"] = $_POST["pseudo"];
				break;
			}
		}
	}
	
	$conn->close();
	
	header('Location: ../myplace/');
	die();
?>
