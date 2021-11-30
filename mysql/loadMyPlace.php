<?php

    //connect
    require_once('./mysql_connect_ro.php');
    require_once('../mysql/load_session.php');
	
	if(!isset($_SESSION["session_user"]))
	{
		// User invalid session
		$conn->close();
		die();
	}
	$_session_user = $_SESSION["session_user"];
	$_session_username = $_SESSION["session_username"]; 
	
	$style_lcds = "border: outset 2px #000000;".$def_style;
	$style_tdll = "border: outset 2px #ffcb3b;".$def_style;
	$style_pc = "border: outset 2px #ededed;".$def_style;
	$style_lv = "border: outset 2px #c27dff;".$def_style;
     
    $query = "select cle_card,nbr,`info`,`ALL_CARDS`.`Nom`,`ALL_CARDS`.`Type`,`ALL_CARDS`.`carte`,`ALL_CARDS`.`extension` from `MARKET` inner join `ALL_CARDS` on `cle_card`=`ALL_CARDS`.`cle` where `cle_user`=(select `cle` from `ALL_USERS` where `pseudo`='".$_session_username."')";
	$result = $conn->query($query);
	if ($result->num_rows > 0)
	{
		// output data of each row
		while($row = mysqli_fetch_array($result))
        { 
			$cle_card = $row ['cle_card'];
			$nom = $row ['Nom'];
			$type = $row ['Type'];
			$nbr = $row ['nbr'];
			$info = $row ['info'];
			$jpg = $row ['carte'];
			$ext = $row['extension'];
			$style = "";
			
			if(strcmp($ext, "TDLL") == 0)
			{
				$style = $style_tdll;
			}
			else if(strcmp($ext, "PC") == 0)
			{
				$style = $style_pc;
			}
			else if(strcmp($ext, "LV") == 0)
			{
				$style = $style_lv;
			}
			else 
			{
				$style = $style_lcds;
			}

			echo '<a id="ddb_response" cle="'.$cle_card.'" jpg="data:image/jpeg;base64,'.base64_encode( $jpg ).'" jpg_style="'.$style.'" nom="'.$nom.'" type="'.$type.'" nbr="'.$nbr.'" info="'.$info.'">';
			echo '</a>';
		}
	}
	else
	{
		echo "0 results";
	}

    //disconnect
    $conn->close();
    die();
?>
