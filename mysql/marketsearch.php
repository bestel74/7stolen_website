<?php

    //connect
    require_once('./mysql_connect_ro.php');
    
	$nom = $conn->real_escape_string($_POST['nom']);
    $classe = $conn->real_escape_string($_POST['classe']); 
    $faction = $conn->real_escape_string($_POST['faction']);
    $type = $conn->real_escape_string($_POST['type']);
    $capa = $conn->real_escape_string($_POST['capa']);
    $cout_min = $conn->real_escape_string($_POST['cout_min']);
    $cout_max = $conn->real_escape_string($_POST['cout_max']);
    $lcds = $conn->real_escape_string($_POST['lcds']);
    $tdll = $conn->real_escape_string($_POST['tdll']);
    $pc = $conn->real_escape_string($_POST['pc']);
    $lv = $conn->real_escape_string($_POST['lv']);
    
    $ext_array = array();
	if($lcds == 'true') array_push($ext_array, "'LCDS'");
	if($tdll == 'true') array_push($ext_array, "'TDLL'");
	if($pc == 'true') array_push($ext_array, "'PC'");
	if($lv == 'true') array_push($ext_array, "'LV'");

	$extension_filter = implode(', ', $ext_array);
	
	$style_lcds = "border: outset 2px #000000;".$def_style;
	$style_tdll = "border: outset 2px #ffcb3b;".$def_style;
	$style_pc = "border: outset 2px #ededed;".$def_style;
	$style_lv = "border: outset 2px #c27dff;".$def_style;
     
    $query = "select `cle_card`,`nbr`,`info`,`ALL_CARDS`.`Nom`,`ALL_CARDS`.`Type`,`ALL_USERS`.`pseudo`,`ALL_CARDS`.`carte`,`ALL_CARDS`.`extension` from `MARKET` inner join `ALL_CARDS` on `cle_card`=`ALL_CARDS`.`cle` inner join `ALL_USERS` on `cle_user`=`ALL_USERS`.`cle` where `extension` in (".$extension_filter.") and `nom` like '%" .$nom. "%' and `classe` like '%" .$classe. "%' and `faction` like '%" .$faction. "%' and `type` like '%" .$type. "%' and (`capa` like '%" .$capa. "%' or `texte` like '%" .$capa. "%') and `cout`>=".$cout_min." and `cout`<=".$cout_max." limit 100";
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
			$pseudo = $row ['pseudo'];
			$jpg = $row['carte'];
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

			echo '<a id="ddb_response" cle="'.$cle_card.'" jpg="data:image/jpeg;base64,'.base64_encode( $jpg ).'" jpg_style="'.$style.'" nom="'.$nom.'" type="'.$type.'" nbr="'.$nbr.'" info="'.$info.'" pseudo="'.$pseudo.'">';
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
