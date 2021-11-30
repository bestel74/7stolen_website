<!DOCTYPE HTML>
<!--
	Stellar by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>7 Stolen - My Place</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="../assets/css/main.css" />
		<noscript><link rel="stylesheet" href="../assets/css/noscript.css" /></noscript>
		<script src="https://www.google.com/recaptcha/api.js" async defer></script>
		<link rel="icon" type="image/png" href="../images/icon.png" />
	</head>
	<body class="is-preload" onload="load_myplace()">

		<!-- <div class="background-image"></div> -->
		
		<?php
			require_once '../mysql/recaptchalib.php';
			require_once('../mysql/load_session.php');
		?>
		
		<script>
		
			function disconnect_user()
			{
				session_destroy();
			}
			
		</script>

		<!-- Wrapper -->
		<div id="wrapper">
			<!-- Main -->
			<div id="main">
				
				<!-- Nav -->
				<section class="main special">
						<ul id="menu" class="features" >
							<li><a href="../">Liste des cartes</a></li>
							<li><a href="../marketplace/">Market Place</a></li>
							<li><a href="../myplace/" class="active">My Place</a></li>
						</ul>
				</section>
				
				<?php
					$_session_user="";
					$_session_username="";

					if(isset($_SESSION["session_user"]))
					{
						$_session_user = $_SESSION["session_user"];
						$_session_username = $_SESSION["session_username"];
					}

					// Check if it's a real session
					if($_session_user == crypt($_session_username, $_session_username.$_session_username))
					{
						echo '<div style="color:white;">Connected with '.$_SESSION["session_username"];
						echo '	&nbsp;	&nbsp;	&nbsp;    |  	&nbsp;	&nbsp;	&nbsp; ';
						echo '<a  href="../mysql/logout.php">Se déconnecter</a></div>';
					}
					// If there is no current session, go to login page
					else
					{
						echo '
						<section class="main special">
							<div style="display: in-line;">
								<header class="">
									<h1 style="color: #ffffff;">Connexion</h1>
								</header>
							
								<form action="../mysql/loginUser.php" method="POST">
									<input type="text" placeholder="Pseudo" name="pseudo" required>
									<input type="password" name="password" required>
									<input type="submit" value="Login">
								</form>
							</div>
							
								<br/>
								<br/>
								<br/>

							<div>
								<header class="">
									<h1 style="color: #ffffff;">Créer un compte</h1>
								</header>
								
								<form action="../mysql/createUser.php" method="POST">
								
								<div style="display: in-line;">
									<label for="pseudo">Pseudo (discord) : </label>
									<input type="text" name="pseudo" required>
									<br />
								</div>
									
									<label for="password">Password : </label>
									<input type="password" name="password" required>
									<br />
									
									<label for="email">Email : </label>
									<input type="email" name="email">
									<br />
									(L\'email n\'est pas obligatoire et ne servira qu\'à récupérer son mot de passe dans une prochaine version, peut-être...)
									
									<div class="g-recaptcha" data-sitekey="6LdVKEIdAAAAALdEZTywm0GDZc9tMFa4ZDl5KQc5"></div>
									<br/>
									<input type="submit" value="Créer un nouveau compte">
								</form>


							</div>
						
						</section>';
						
						// TEST sitekey : <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></div>

						die();
					}
				?>

				<section class="main special">

						<header class="">
							<h1 style="color: #ffffff;">Vos cartes à vendre/échanger</h1>
						</header>
						
					<table id="marketplace">
						<thead>
							<tr>
								<td>Nom</td>
								<td>Type</td>
								<td>Nbr.</td>
								<td>Info</td>
								<td></td>
							</tr>
						</thead>
						<tbody id="marketplace_vendre">

						</tbody>

					</table>
					
					<div style="display: in-line;">
						
						<button onclick="save_changes()">Enregistrer les changements</button>
						<br/>
						(Cette action ne peut pas être annulée)
					</div>
					
					<br/>
					
					Partagez votre liste avec ce lien : <a> <?php print("https://7stolen.choupis.ovh/marketplace/placeof.php?pseudo=" . urlencode($_SESSION["session_username"])); ?> </a>
					
					<div id="saving_zone">
						
					</div>
				
				</section>
				
				
				<script>
					function load_myplace() {
						var text = document.createTextNode("Chargement en cours...");
						document.getElementById("saving_zone").appendChild(text);

						var xmlhttp=new XMLHttpRequest();
						xmlhttp.onreadystatechange=function() {
							if (this.readyState==4 && this.status==200) {
								
								var doc = new DOMParser().parseFromString(this.responseText, "text/html");
								var a_list = doc.getElementsByTagName("a");

								for(let i = 0; i < a_list.length ; i++)
								{
									var r = a_list[i];

									// Get all data
									var cle = r.getAttribute('cle');
									var type = r.getAttribute('type');
									var nom = r.getAttribute('nom');
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
									
									cell1.innerHTML = nom_carte;
									cell2.innerHTML = type;
									cell3.innerHTML = nbr;
									cell4.innerHTML = '<input style="width:100%; text-align: left;" type="text" value="'+info+'">';
									cell5.innerHTML = '<button onclick="delete_tr(this.parentNode.parentNode)">X</button>';
								}
								
								const clean_load = document.getElementById("saving_zone");
								clean_load.textContent = '';
							}
						}

						xmlhttp.open("POST", "../mysql/loadMyPlace.php", true);
						xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
						xmlhttp.send();
					}
					
					function save_changes()
					{
						var text = document.createTextNode("Enregistrement en cours...");
						document.getElementById("saving_zone").appendChild(text);
						
						php_clean_mysql();

						var all_tr = document.getElementById("marketplace_vendre").getElementsByTagName('tr');
						for(let i = 0; i < all_tr.length ; i++)
						{
							var tr = all_tr[i];
							
							var cle = tr.getAttribute('cle');
							var nbr = tr.getElementsByTagName('td')[2].textContent;
							var info = tr.getElementsByTagName('td')[3].childNodes[0].value;

							php_write_mysql(cle, nbr, info);
						}
						
						const clean_load = document.getElementById("saving_zone");
						clean_load.textContent = '';
						
						alert('Enregistrement terminé !');
					}
					
					function php_write_mysql(cle, nbr, info)
					{
						var xmlhttp=new XMLHttpRequest();
						xmlhttp.open("POST", "../mysql/addToMyPlace.php", true);
						xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
						xmlhttp.send("cle="+ cle + "&nbr=" + nbr + "&info="+ info);
					}
					
					function php_clean_mysql()
					{
						var xmlhttp=new XMLHttpRequest();
						xmlhttp.open("POST", "../mysql/cleanMyPlace.php", true);
						xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
						xmlhttp.send();
					}
					
				</script>


				<!-- Bar de recherche -->
				<section id="searchbar" class="main special">

					
					
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

					function showResult() {
						var text = document.createTextNode("Chargement en cours...");
						document.getElementById("loading_zone").appendChild(text);

						var xmlhttp=new XMLHttpRequest();
						xmlhttp.onreadystatechange=function() {
							if (this.readyState==4 && this.status==200) {
								document.getElementById("livesearch").innerHTML=this.responseText;
								
								const clean_load = document.getElementById("loading_zone");
								clean_load.textContent = '';
							}
						}

						xmlhttp.open("POST", "../mysql/livesearch.php", true);
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
					
					function test_ifexist(name, table)
					{
						for(var tableIt=0 ; tableIt < table.childElementCount ; tableIt++) {
							var columns = table.getElementsByTagName('td');
							for(columnIt = 0; columnIt < columns.length; columnIt++) {
								var column = columns[columnIt];
								if(column.innerHTML === name)
								{
									columns[columnIt+2].innerHTML = parseInt(columns[columnIt+2].innerHTML)+1;
									return true;
								}
							}
						}
						
						return false;
					}
					

					function card_selected(card)
					{
						// Get card type to define card cat.
						var xmlhttp=new XMLHttpRequest();
						xmlhttp.onreadystatechange=function() {
							if (this.readyState==4 && this.status==200) {

								var doc = new DOMParser().parseFromString(this.responseText, "text/html");
								var r = doc.getElementsByTagName("a")[0];
								
								// Get all data
								var cle = r.getAttribute('cle');
								var faction = r.getAttribute('faction');
								var type = r.getAttribute('type');
								var nom = r.getAttribute('nom');
								var classe = r.getAttribute('classe');
								var capa = r.getAttribute('capa');
								var ec = r.getAttribute('ec');
								var ft = r.getAttribute('ft');
								var cout = r.getAttribute('cout');
								var tmp = r.getAttribute('tmp');
								var atq = r.getAttribute('atq');
								var fv = r.getAttribute('fv');
								var bou = r.getAttribute('bou');
								var ad_c = r.getAttribute('ad_c');
								var ad_r = r.getAttribute('ad_r');

								// Create new line
								var row;
								if(test_ifexist(nom, marketplace_vendre))
								{
									// If the card is already in the table, just add one more (increment col. nbr)
									return;
								}
								else
								{
									row = marketplace_vendre.insertRow();
								}

								row.setAttribute("cle", cle);
								var cell1 = row.insertCell(0);
								var cell2 = row.insertCell(1);
								var cell3 = row.insertCell(2);
								var cell4 = row.insertCell(3);
								var cell5 = row.insertCell(4);

								
								cell1.innerHTML = nom;
								cell2.innerHTML = type;
								cell3.innerHTML = 1;
								cell4.innerHTML = '<input style="width:100%;" type="text" placeholder="(Modalités d\'échange, prix, ...)">';
								cell5.innerHTML = '<button onclick="delete_tr(this.parentNode.parentNode)">X</button>';
							}
						}

						xmlhttp.open("POST", "../mysql/getstats.php", true);
						xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
						xmlhttp.send("nom="+card.getAttribute('alt'));
						
					}
					
					function delete_tr(tr)
					{
						var nbr = tr.getElementsByTagName('td')[2].innerHTML;
						if(nbr > 1)
						{
							// Just remove one card
							tr.getElementsByTagName('td')[2].innerHTML = nbr-1;
						}
						else
						{
							tr.remove();
						}
					}

					
					</script>

				</section>

				<section class="main special">

					<ul id="livesearch" class="features">
						
					</ul>

				</section>


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
