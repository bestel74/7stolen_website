<!DOCTYPE HTML>
<!--
	Stellar by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>7 Stolen - Market Place</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="../assets/css/main.css" />
		<noscript><link rel="stylesheet" href="../assets/css/noscript.css" /></noscript>
		<link rel="icon" type="image/png" href="../images/icon.png" />
	</head>
	<body class="is-preload" onload="showResult()">

		<!-- <div class="background-image"></div> -->

		<!-- Wrapper -->
		<div id="wrapper">
			<!-- Main -->
			<div id="main">
				
				<!-- Nav -->
				<section class="main special">
						<ul id="menu" class="features" >
							<li><a href="../">Liste des cartes</a></li>
							<li><a href="../marketplace/">Market Place</a></li>
							<li><a href="../myplace/">My Place</a></li>
						</ul>
				</section>
				
				<section class="main special">

						<header class="">
							<h1 style="color: #ffffff;">Les cartes à vendre/échanger de <?php Print(urldecode($_GET['pseudo'])); ?> </h1>
						</header>
						
					<table id="marketplace">
						<thead>
							<tr>
								<td>Nom</td>
								<td>Type</td>
								<td>Nbr.</td>
								<td>Info</td>
							</tr>
						</thead>
						<tbody id="marketplace_vendre">

						</tbody>

					</table>
					
					<div id="saving_zone">
						
					</div>
				
				</section>


				<script>

				var table_vendre = document.getElementById("marketplace_vendre");

				function showResult() {
					var text = document.createTextNode("Chargement en cours...");
					document.getElementById("saving_zone").appendChild(text);

					var xmlhttp=new XMLHttpRequest();
					xmlhttp.onreadystatechange=function() {
						if (this.readyState==4 && this.status==200) {
							var doc = new DOMParser().parseFromString(this.responseText, "text/html");
							var a_list = doc.getElementsByTagName("a");
							
							// Time to remove all old value 
							table_vendre.innerHTML = '';
							for(let i = 0;i < a_list.length; i++)
							{
								var a = a_list[i];

								add_table_row(a);
							}

							
							const clean_load = document.getElementById("saving_zone");
							clean_load.textContent = '';
						}
					}
					
					var jpseudo = "<?php Print(urldecode($_GET['pseudo'])); ?>";

					xmlhttp.open("POST", "../mysql/getPlaceOf.php", true);
					xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
					xmlhttp.send("pseudo="+jpseudo);
				}

				function add_table_row(r)
				{
					// Get all data
					var nom = r.getAttribute('nom');
					var type = r.getAttribute('type');
					var nbr = r.getAttribute('nbr');
					var info = r.getAttribute('info');
					var jpg = r.getAttribute('jpg');
					var jpg_style = r.getAttribute('jpg_style');
					
					// Create new line
					var row = marketplace_vendre.insertRow();

					var cell1 = row.insertCell(0);
					var cell2 = row.insertCell(1);
					var cell3 = row.insertCell(2);
					var cell4 = row.insertCell(3);
					
					var nom_carte = '<div class="hover_card" style="text-align: left;"> <a>'+nom+'<span><img src="'+jpg+'" class="image" style="'+jpg_style+'"/></span></a> </div>';

					cell1.innerHTML = nom_carte;
					cell2.innerHTML = type;
					cell3.innerHTML = nbr;
					cell4.innerHTML = '<div style="text-align: left;"> '+info+' </div>';
				}
				
				</script>

			</div>
			
		</div>

		

		<footer class="copyright" style="text-align: center;">
			<strong style="color: #ffffff;"> Ce site ne traque pas ses visiteurs </strong>
			
			<br/> 
			
			Design: <a href="https://html5up.net">HTML5 UP</a>
			
		</footer>


		<!-- Scripts -->
			<script src="../assets/js/jquery.min.js"></script>
			<script src="../assets/js/jquery.scrollex.min.js"></script>
			<script src="../assets/js/jquery.scrolly.min.js"></script>
			<script src="../assets/js/browser.min.js"></script>
			<script src="../assets/js/breakpoints.min.js"></script>
			<script src="../assets/js/util.js"></script>
			<script src="../assets/js/main.js"></script>

	</body>
</html>
