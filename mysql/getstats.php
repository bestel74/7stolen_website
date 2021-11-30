<?php

	if(empty($_POST))
	{
		die();
	}

    //connect
    require_once('./mysql_connect_ro.php');
    
    $nom = $conn->real_escape_string($_POST['nom']);
           
     
    $query = "select cle,faction,type,nom,classe,capa,ec,ft,cout,tmp,atq,fv,bou,ado_cond,ado_rev from ALL_CARDS where `Nom`=\"".$nom."\" limit 1";
    
	$result = $conn->query($query);
	if ($result->num_rows > 0)
	{
		$count=0;
		// output data of each row
		while($row = mysqli_fetch_array($result))
        { 
			$cle = $row ['cle'];
			$faction = $row ['faction'];
			$type = $row ['type'];
			$nom = $row ['nom'];
			$classe = $row ['classe'];
			$capa = $row ['capa'];
			$ec = $row ['ec'];
			$ft = $row ['ft'];
			$cout = $row ['cout'];
			$tmp = $row ['tmp'];
			$atq = $row ['atq'];
			$fv = $row ['fv'];
			$bou = $row ['bou'];
			$ad_c = $row ['ado_cond'];
			$ad_r = $row ['ado_rev'];

			echo '<a id="ddb_response" cle="'.$cle.'" faction="'.$faction.'" type="'.$type.'" nom="'.$nom.
			'" classe="'.$classe.'" capa="'.$capa.'" ec="'.$ec.
			'" ft="'.$ft.'" cout="'.$cout.'" tmp="'.$tmp.
			'" atq="'.$atq.'" fv="'.$fv.'" bou="'.$bou.
			'" ad_c="'.$ad_c.'" ad_r="'.$ad_r.'" >';
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
