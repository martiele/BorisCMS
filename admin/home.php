<?php 
require_once('../Connections/std_conn.php');
require_once('../funzioni.php');

$MM_authorizedUsers = "1,2,3";
require_once("restrict.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"> 
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?php echo $_SESSION["www_title"]; ?> - Admin Home</title>
		<?php require_once("header.php"); ?>
	</head>
  
	<body><div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->
		
		<?php require_once("sidebar_messages.php"); ?>
		
		<div id="main-content"> <!-- Main Content Section with everything -->
			
            <?php require_once("top_bar.php"); ?>


			<div class="notification success png_bg">
				<a href="#" class="close"><img src="resources/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
				<div>
					Questa pagina contiene le informazioni in evidenza per l'amministratore
				</div>
			</div>
            
            
			<div class="content-box column-left">
				
				<div class="content-box-header">
					
					<h3>Panoramica</h3>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
					
					<div class="tab-content default-tab">
					
						<h4>Funzionalità di quest'area amministrativa</h4>
						<p>Tramite questo pannello di controllo sarà possibile gestire i contenuti dell'area riservata, comprese le categorie documentali e gli utenti che possono accedervi.</p>
						<p>In generale è possibile inserire / modificare / eliminare i contenuti delle 3 sezioni: categorie, articoli, utenti.</p>
                        <p>Infine, tramite la sezione "Invio Notifiche" sar&agrave; possibile inviare delle email automatiche agli iscritti per comunicare la presenza di nuovi contenuti nella'rea riservata del sito.</p>
						
					</div> <!-- End #tab3 -->        
					
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->
			
			<div class="content-box column-right closed-box">
				
				<div class="content-box-header"> <!-- Add the class "closed" to the Content box header to have it closed by default -->
					
					<h3>Assistenza</h3>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
					
					<div class="tab-content default-tab">
					
						<h4>In caso di necessit&agrave;</h4>
						<p>Per assistenza potete scrivermi in ogni momento:<br />
						  via mail 
a <a href="mailto:ing.martini@gmail.com">ing.martini@gmail.com</a><br />
o  al (+39) 340 3468558</p>
						
					</div> <!-- End #tab3 -->        
					
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->
			<div class="clear"></div>
			
			
			<!-- Start Notifications -->
		
			<!--
			<div class="notification error png_bg">
				<a href="#" class="close"><img src="resources/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
				<div>
					Attenzione!! Alcune funzionalità non sono ancora attive.
				</div>
			</div>
            -->
			

			
            <?php require_once("footer.php"); ?>

			
		</div> <!-- End #main-content -->
	</div></body>
  
</html>
