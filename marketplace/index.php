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
							<li><a href="../marketplace/" class="active">Market Place</a></li>
							<li><a href="../myplace/">My Place</a></li>
						</ul>
				</section>

				<!-- Bar de recherche -->
				<section id="searchbar" class="main special">
					<header class="">
						<h1 style="color: #ffffff;">Market Place</h1>
					</header>
				
					<form>
						<input type="text" placeholder="Nom de la carte" 						name="carte" size="30">
						<input type="text" placeholder="Classe de la carte" 					name="classe" size="30">
						<input type="text" placeholder="Royaume (neutre, nuit sans fin, ...)" 	name="faction" size="30">
						<input type="text" placeholder="Type (ange, golem, ...)" 				name="type" size="30">
						<input type="text" placeholder="Capacité (Berserk, cata, ...)" 		name="capa" size="30">

						<div style="display: in-line;">

							<input type="range" name="cout_min" value ="0" min="0" max="30" onchange="showResult(this.value)" oninput="this.nextElementSibling.value = this.value">
							<output>0</output>
							(coût min)

							<input type="range" name="cout_max" value ="30" min="0" max="30" onchange="showResult(this.value)" oninput="this.nextElementSibling.value = this.value">
							<output>30</output>
							(coût max)
							
							<br/>

							<input type="checkbox" name="ext_lcds" checked>
							<label id="ext_lcds" for="ext_lcds" onclick="toggleCheckbox(this); checkbox_up_search()" >LCDS</label>

							<input type="checkbox" name="ext_tdll" checked>
							<label id="ext_tdll" for="ext_tdll" onclick="toggleCheckbox(this); checkbox_up_search()">TDLL</label>

							<input type="checkbox" name="ext_pc" checked>
							<label id="ext_pc" for="ext_pc" onclick="toggleCheckbox(this); checkbox_up_search()">PUCE</label>

							<input type="checkbox" name="ext_lv" checked>
							<label id="ext_lv" for="ext_lv" onclick="toggleCheckbox(this); checkbox_up_search()">LVSA</label>
						</div>
					</form>
					
					<div id="loading_zone">
						
					</div>
				</section>

				<section class="main special">
					<table id="marketplace">
						<thead>
							<tr>
								<td>Pseudo</td>
								<td>Nom</td>
								<td>Type</td>
								<td>Nbr.</td>
								<td>Info</td>
							</tr>
						</thead>
						<tbody id="marketplace_vendre">
						</tbody>

					</table>
				</section>
						
				<script>
					
				// Toggle checkbox
				function toggleCheckbox(element)
				{
					var chbox = document.getElementsByName( element.getAttribute('id') )[0];
					chbox.checked = !chbox.checked;
				}

				// Only one timer to rule them all
				var timer = null;
				function addTextAreaCallback(textArea, callback, delay) {
					textArea.onkeydown = function() {
						if (timer) {
							window.clearTimeout(timer);
						}
						timer = window.setTimeout( function() {
							timer = null;
							callback();
						}, delay );
					};
					textArea = null;
				}
				
				function checkbox_up_search() {
						if (timer) {
							window.clearTimeout(timer);
						}
						timer = window.setTimeout( function() {
							timer = null;
							showResult();
						}, 1500 );
					}

				addTextAreaCallback( document.getElementsByName("carte")[0], showResult, 1500);
				addTextAreaCallback( document.getElementsByName("classe")[0], showResult, 1500);
				addTextAreaCallback( document.getElementsByName("faction")[0], showResult, 1500);
				addTextAreaCallback( document.getElementsByName("type")[0], showResult, 1500);
				addTextAreaCallback( document.getElementsByName("capa")[0], showResult, 1500);
				
				var table_vendre = document.getElementById("marketplace_vendre");

				function showResult() {
					var text = document.createTextNode("Chargement en cours...");
					document.getElementById("loading_zone").appendChild(text);

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

							
							const clean_load = document.getElementById("loading_zone");
							clean_load.textContent = '';
						}
					}

					xmlhttp.open("POST", "../mysql/marketsearch.php", true);
					xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
					xmlhttp.send(
						"nom="+document.getElementsByName("carte")[0].value +
						"&classe="+document.getElementsByName("classe")[0].value +
						"&faction="+document.getElementsByName("faction")[0].value +
						"&type="+document.getElementsByName("type")[0].value +
						"&capa="+document.getElementsByName("capa")[0].value +
						"&cout_min="+document.getElementsByName("cout_min")[0].value + 
						"&cout_max="+document.getElementsByName("cout_max")[0].value + 
						"&lcds="+document.getElementsByName("ext_lcds")[0].checked +
						"&tdll="+document.getElementsByName("ext_tdll")[0].checked +
						"&pc="+document.getElementsByName("ext_pc")[0].checked +
						"&lv="+document.getElementsByName("ext_lv")[0].checked);
				}

				function add_table_row(r)
				{
					// Get all data
					var cle = r.getAttribute('cle');
					var pseudo = r.getAttribute('pseudo');
					var nom = r.getAttribute('nom');
					var type = r.getAttribute('type');
					var nbr = r.getAttribute('nbr');
					var info = r.getAttribute('info');
					var jpg = r.getAttribute('jpg');
					var jpg_style = r.getAttribute('jpg_style');
					
					
					// Create new line
					var row = marketplace_vendre.insertRow();

					row.setAttribute("cle", cle);
					var cell1 = row.insertCell(0);
					var cell2 = row.insertCell(1);
					var cell3 = row.insertCell(2);
					var cell4 = row.insertCell(3);
					var cell5 = row.insertCell(4);

					var nom_carte = '<div class="hover_card" style="text-align: left;"> <a>'+nom+'<span><img src="'+jpg+'" class="image" style="'+jpg_style+'"/></span></a> </div>';
					
					cell1.innerHTML = '<div style="text-align: left;"> '+pseudo+' </div>';
					cell2.innerHTML = nom_carte;
					cell3.innerHTML = type;
					cell4.innerHTML = nbr;
					cell5.innerHTML = '<div style="text-align: left;"> '+info+' </div>';
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
