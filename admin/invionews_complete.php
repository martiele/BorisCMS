<?php 
require_once('../Connections/std_conn.php');
require_once('../funzioni.php');

$MM_authorizedUsers = "1,2";
require_once("restrict.php");

$riepilogo_invii = "";

if($_POST["nomeform"]=="vv"){

	include("../PHPMailer-master/config_consorzio.php");
	

	/*
	$mail = new phpmailer();
	$mail->From     = "gare@clsl.it";
	$mail->FromName = "Consorzio Leonardo Servizi e Lavori";
	//$mail->Host     = $global_vars["mail_host"];
	$mail->IsHTML(true);
	$mail->SetLanguage("it", '../phpmailer/language/');

	$mail->SMTPDebug = 2; //Alternative to above constant
	$mail->isSMTP();  // tell the class to use SMTP
	$mail->SMTPAuth   = true;                // enable SMTP authentication
	$mail->SMTPSecure = false;
	$mail->Port       = 465;                  // set the SMTP port
	$mail->Host       = "mail-plesk.ised.it"; // SMTP server
	$mail->Username   = "gare@clsl.it"; // SMTP account username
	$mail->Password   = "Vv6ad1@8";
	*/
	
	/* *** */
	// Vado a popolare la lista degli invii con la coppia articolo/news
	/* *** */
	//categorie
	mysqli_select_db($std_conn, $database_std_conn);
	$query_RecordsetCat = sprintf("SELECT A.*, B.nome FROM (dny_categoria as A LEFT JOIN dny_categoria_lingua as B ON A.id = B.id_ref) WHERE A.deleted=0 AND A.id_linea=1 AND (B.id_lingua=%s OR B.id_lingua is Null) ORDER BY A.ordinamento ASC", 
						GetSQLValueString($_SESSION["linguadefault"],"int"));
	$RecordsetCat = mysqli_query($std_conn, $query_RecordsetCat) or die(mysqli_error($std_conn));
	$row_RecordsetCat = mysqli_fetch_assoc($RecordsetCat);
	$totalRows_RecordsetCat = mysqli_num_rows($RecordsetCat);
	if($totalRows_RecordsetCat>0){
		do{
			$idcat = $row_RecordsetCat["id"];
			if(isset($_POST["cat_".$idcat]) && ($_POST["cat_".$idcat]=="1")){

				// Recupero tutti gli utenti che ricevono notifiche da questa categoria
				$query_ciclo_utenti = sprintf("SELECT dny_utente_newsletter.* FROM dny_utente_newsletter JOIN dny_utente_newsletter_categoria ON dny_utente_newsletter.id = dny_utente_newsletter_categoria.id_utente_newsletter WHERE dny_utente_newsletter.deleted=0 AND dny_utente_newsletter.is_attivo=1 AND dny_utente_newsletter_categoria.id_categoria=%s", 
						GetSQLValueString($idcat,"int"));
				$Recordset_ciclo_utenti = mysqli_query($std_conn, $query_ciclo_utenti) or die(mysqli_error($std_conn));
				$row_Recordset_ciclo_utenti = mysqli_fetch_assoc($Recordset_ciclo_utenti);
				$totalRows_Recordset_ciclo_utenti = mysqli_num_rows($Recordset_ciclo_utenti);
											
				mysqli_select_db($std_conn, $database_std_conn);
				$query_RecordsetSottoCat = sprintf("SELECT * FROM dny_sottocategoria WHERE deleted=0 AND is_attivo=1 AND id_categoria=%s", 
					GetSQLValueString($idcat,"int"));
				$RecordsetSottoCat = mysqli_query($std_conn, $query_RecordsetSottoCat) or die(mysqli_error($std_conn));
				$row_RecordsetSottoCat = mysqli_fetch_assoc($RecordsetSottoCat);
				$totalRows_RecordsetSottoCat = mysqli_num_rows($RecordsetSottoCat);
				if($totalRows_RecordsetSottoCat>0){
					do{
						$idsubcat = $row_RecordsetSottoCat["id"];
					
						// Devo inserire le richieste di conferma - INIZIO
						
						// 1 - ciclo i singoli articoli di questa sottocategoria
						$query_ciclo_articoli = sprintf("SELECT * FROM dny_news WHERE deleted=0 AND is_attivo=1 AND segnalato_newsletter=0 AND (richiedi_interesse=1 OR is_ristretta_negoziata=1) AND id_sottocategoria=%s", 
							GetSQLValueString($idsubcat,"int"));
						//echo $query_ciclo_articoli;
						$Recordset_ciclo_articoli = mysqli_query($std_conn, $query_ciclo_articoli) or die(mysqli_error($std_conn));
						$row_Recordset_ciclo_articoli = mysqli_fetch_assoc($Recordset_ciclo_articoli);
						$totalRows_Recordset_ciclo_articoli = mysqli_num_rows($Recordset_ciclo_articoli);
						if($totalRows_Recordset_ciclo_articoli>0){
							do{
								//echo "<br />art: ".$row_Recordset_ciclo_articoli["id"];
								// 2 - ciclo gli utenti della categoria principale
								if($totalRows_Recordset_ciclo_utenti>0){
									do{
										//echo " - ute: ".$row_Recordset_ciclo_utenti["id"];
										// 3 - controllo che non esista già la coppia articolo/utente
										$query_ciclo_notifiche = sprintf("SELECT * FROM dny_news_letta_utente WHERE id_news=%s AND id_utente_newsletter=%s", 
											GetSQLValueString($row_Recordset_ciclo_articoli["id"],"int"),
											GetSQLValueString($row_Recordset_ciclo_utenti["id"],"int"));
										$Recordset_ciclo_notifiche = mysqli_query($std_conn, $query_ciclo_notifiche) or die(mysqli_error($std_conn));
										$row_Recordset_ciclo_notifiche = mysqli_fetch_assoc($Recordset_ciclo_notifiche);
										$totalRows_Recordset_ciclo_notifiche = mysqli_num_rows($Recordset_ciclo_notifiche);
										if(!$totalRows_Recordset_ciclo_notifiche){
											//echo " - non c'è";
											// 4 - se non c'è inserisco tale coppia			
											$insert_notifica = sprintf("INSERT INTO dny_news_letta_utente (id_news, id_utente_newsletter, data_notifica_inviata) VALUES (%s, %s, %s)", 
											GetSQLValueString($row_Recordset_ciclo_articoli["id"],"int"),
											GetSQLValueString($row_Recordset_ciclo_utenti["id"],"int"),
											GetSQLValueString(data_a_database(date("d/m/Y")),"date"));
											mysqli_select_db($std_conn, $database_std_conn);
											mysqli_query($std_conn, $insert_notifica) or die(mysqli_error($std_conn)." - ".$insert_notifica);
										}
										mysqli_free_result($Recordset_ciclo_notifiche);
					
									}while($row_Recordset_ciclo_utenti = mysqli_fetch_assoc($Recordset_ciclo_utenti)); 
									mysqli_data_seek($Recordset_ciclo_utenti, 0);
									$row_Recordset_ciclo_utenti = mysqli_fetch_assoc($Recordset_ciclo_utenti);
								}			
							}while($row_Recordset_ciclo_articoli = mysqli_fetch_assoc($Recordset_ciclo_articoli)); 
						}			
						mysqli_free_result($Recordset_ciclo_articoli);
						
					}while($row_RecordsetSottoCat = mysqli_fetch_assoc($RecordsetSottoCat)); 
				}
				mysqli_free_result($RecordsetSottoCat);
							
				//ciclo utenti
				mysqli_free_result($Recordset_ciclo_utenti);
				
			}

		}while($row_RecordsetCat = mysqli_fetch_assoc($RecordsetCat)); 
	}
	mysqli_free_result($RecordsetCat);	

	/* *** FINE */
	//  Vado a popolare la lista degli invii con la coppia articolo/news 
	/* *** FINE */
	
	//Adesso invio le email
	$riepilogo_invii_std .= "<h3>Invii email gare STANDARD</h3>";
	$riepilogo_invii_rn .= "<p>&nbsp;</p><h3>Invii email gare RISTRETTE/NEGOZIATE</h3>";
	
	//utenti
	mysqli_select_db($std_conn, $database_std_conn);
	$query_RecordsetUtent = sprintf("SELECT * FROM dny_utente_newsletter WHERE deleted=0 AND is_attivo=1 ORDER BY ordinamento ASC");
	$RecordsetUtent = mysqli_query($std_conn, $query_RecordsetUtent) or die(mysqli_error($std_conn));
	$row_RecordsetUtent = mysqli_fetch_assoc($RecordsetUtent);
	$totalRows_RecordsetUtent = mysqli_num_rows($RecordsetUtent);
	if($totalRows_RecordsetUtent>0){
		do{

			$invia_mail = false;
			$categorie_invio = "";
			
			//categorie
			mysqli_select_db($std_conn, $database_std_conn);
			$query_RecordsetCat = sprintf("SELECT A.*, B.nome FROM (dny_categoria as A LEFT JOIN dny_categoria_lingua as B ON A.id = B.id_ref) WHERE A.deleted=0 AND A.id_linea=1 AND (B.id_lingua=%s OR B.id_lingua is Null) ORDER BY A.ordinamento ASC", 
								GetSQLValueString($_SESSION["linguadefault"],"int"));
			$RecordsetCat = mysqli_query($std_conn, $query_RecordsetCat) or die(mysqli_error($std_conn));
			$row_RecordsetCat = mysqli_fetch_assoc($RecordsetCat);
			$totalRows_RecordsetCat = mysqli_num_rows($RecordsetCat);
			if($totalRows_RecordsetCat>0){
				do{
					$idcat = $row_RecordsetCat["id"];
					if(isset($_POST["cat_".$idcat]) && ($_POST["cat_".$idcat]=="1")){
						
						mysqli_select_db($std_conn, $database_std_conn);
						$query_RecordsetUteCat = sprintf("SELECT * FROM dny_utente_newsletter_categoria WHERE id_utente_newsletter=%s AND id_categoria=%s",
							GetSQLValueString($row_RecordsetUtent["id"],"int"),
							GetSQLValueString($idcat,"int"));
						$RecordsetUteCat = mysqli_query($std_conn, $query_RecordsetUteCat) or die(mysqli_error($std_conn));
						if($row_RecordsetUteCat = mysqli_fetch_assoc($RecordsetUteCat)){
							
							$invia_mail = true;
							$categorie_invio .= ($categorie_invio!="")?", ":"";
							$categorie_invio .= $row_RecordsetCat["nome"];
							
						}
						mysqli_free_result($RecordsetUteCat);
						
						
					}
				}while($row_RecordsetCat = mysqli_fetch_assoc($RecordsetCat)); 
			}
			mysqli_free_result($RecordsetCat);				
			
			if($invia_mail){

				/** **************************** **/
				/** INVIO MAIL STANDARD - INIZIO **/
				/** **************************** **/												
				$mail->Subject	= $_SESSION["www_title"]." - nuovi articoli nell'area riservata del sito.";
				//Email al cliente
				$corpomail = sprintf("<html><body>
				<p>Salve %s,<br /><br />
				sono presenti nuovi articoli sul sito %s per le categorie: <strong>%s</strong>.<br />
				<a href='%s'>Collegati al sito</a> e controlla subito le novit&agrave;.</p>
				<p><strong>Ti ricordiamo che la manifestazione d’interesse va espressa entro e non oltre 3 giorni lavorativi.</strong></p>
				",
					$row_RecordsetUtent["nome"],
					$_SESSION["www_title"],
					$categorie_invio,
					$_SESSION["globalCompleteUrl"]."/area-riservata/?mail=".$row_RecordsetUtent["email"]);
				
				//Ciclo gli articoli di questo utente
				mysqli_select_db($std_conn, $database_std_conn);
				$querystrana = sprintf("SELECT * FROM dny_news_letta_utente WHERE id_utente_newsletter=%s AND data_notifica_inviata=%s",
					GetSQLValueString($row_RecordsetUtent["id"], "int"),
					GetSQLValueString(data_a_database(date("d/m/Y")),"date"));
				$RecordsetNewsUt = mysqli_query($std_conn, $querystrana) or die(mysqli_error($std_conn));
				$row_RecordsetNewsUt = mysqli_fetch_assoc($RecordsetNewsUt);
				$totalRows_RecordsetNewsUt = mysqli_num_rows($RecordsetNewsUt);
				$narts = 0;
				$mailconarticoli = false;
				if($totalRows_RecordsetNewsUt>0){
					$corpomail .= "<p>Elenco nuovi articoli:<br />
								   <table cellpadding='2' cellspacing='2'>
									  <tr>
										<td>Categoria</td>
										<td>Oggetto</td>
										<td>Link</td>
									  </tr>";
					do{
						mysqli_select_db($std_conn, $database_std_conn);
						$query_Recordset1 = sprintf("SELECT A.*, B.nome, C.nome Sottocategoria, D.nome Categoria
						FROM (((( (dny_news as A LEFT JOIN dny_news_lingua as B ON A.id = B.id_ref) 
						LEFT JOIN dny_sottocategoria ON A.id_sottocategoria = dny_sottocategoria.id )
						LEFT JOIN dny_sottocategoria_lingua as C ON dny_sottocategoria.id = C.id_ref)
						LEFT JOIN dny_categoria ON dny_sottocategoria.id_categoria = dny_categoria.id )
						LEFT JOIN dny_categoria_lingua as D ON dny_categoria.id = D.id_ref)			
						WHERE A.deleted=0 AND (A.segnalato_newsletter=0)
										  AND (B.id_lingua=%s OR B.id_lingua is Null) 
										  AND (C.id_lingua=%s OR C.id_lingua is Null) 
										  AND (D.id_lingua=%s OR D.id_lingua is Null) 
										  AND A.is_attivo=1 AND A.id=%s", 
											GetSQLValueString($_SESSION["linguadefault"], "int"),
											GetSQLValueString($_SESSION["linguadefault"], "int"),
											GetSQLValueString($_SESSION["linguadefault"], "int"),
											GetSQLValueString($row_RecordsetNewsUt["id_news"], "int"));
			
						$Recordset1 = mysqli_query($std_conn, $query_Recordset1) or die(mysqli_error($std_conn));
						if($row_Recordset1 = mysqli_fetch_assoc($Recordset1)){
							if($row_Recordset1["is_ristretta_negoziata"]=="0"){
								$mailconarticoli = true;

								$linkgara = $_SESSION["globalCompleteUrl"]."/area-riservata-articolo/?ida=".$row_Recordset1["id"];
								$corpomail .= sprintf("
									<tr>
										<td>%s</td>
										<td>%s</td>
										<td><a href='%s' target='_blank'>%s</a></td>
									</tr>
								",
								htmlentities($row_Recordset1["Categoria"],ENT_QUOTES, "UTF-8")." - ".htmlentities($row_Recordset1["Sottocategoria"],ENT_QUOTES, "UTF-8"),
								htmlentities($row_Recordset1["nome"],ENT_QUOTES, "UTF-8"),
								$linkgara,
								$linkgara);
				
								$narts++;			
							}
						}
						mysqli_free_result($Recordset1);
					}while($row_RecordsetNewsUt = mysqli_fetch_assoc($RecordsetNewsUt)); 
					$corpomail .= "</table></p>";
				}
				mysqli_free_result($RecordsetNewsUt);
				
				$corpomail .= "</body></html>";
				
				$mail->Body = $corpomail;
				$mail_std = $corpomail."<p>&nbsp;</p>";
				//echo "<p>&nbsp;</p><p>&nbsp;</p>".$corpomail;
				$mail->AddAddress($row_RecordsetUtent["email"]);
				//Controllo che ci sia almeno una gara per questo utente altrimenti è inutile inviare la mail
				if(($narts>0)&&($mailconarticoli)){
					if (!$mail->send()) {
					    $riepilogo_invii_std .= '\n<br />Mailer Error: ' . $mail->ErrorInfo;
					} else {
					    $riepilogo_invii_std .= '\n<br />Message sent!';
					}
					$riepilogo_invii_std .= "<br />Inviata mail a <strong>".$row_RecordsetUtent["nome"]."</strong> (" . $row_RecordsetUtent["email"] . ") per le categorie: ".$categorie_invio.".";
				}else{
					$riepilogo_invii_std .= "<br /><strong>NON</strong> Inviata mail a <strong>".$row_RecordsetUtent["nome"]."</strong> (nessun articolo)";
			
				}
				$mail->ClearAddresses();
				/** **************************** **/
				/** INVIO MAIL STANDARD - FINE   **/
				/** **************************** **/				


				/** ***************************************** **/
				/** INVIO MAIL RISTRETTE / NEGOZIATE - INIZIO **/
				/** ***************************************** **/		
				$mail->Subject	= $_SESSION["www_title"]." - nuove gare ristrette/negoziate.";
				//Email al cliente
				$corpomail = sprintf("<html><body>
				<p>Salve %s,<br /><br />
				siamo stati invitati a partecipare alla/e seguente/i gara %s per le categorie: %s.<br /><br />
				<a href='%s'>Collegati al sito</a> e controlla subito le novit&agrave;.</p>",
					$row_RecordsetUtent["nome"],
					$_SESSION["www_title"],
					$categorie_invio,
					$_SESSION["globalCompleteUrl"]."/area-riservata/?mail=".$row_RecordsetUtent["email"]);
				
				//Ciclo gli articoli di questo utente
				mysqli_select_db($std_conn, $database_std_conn);
				$querystrana = sprintf("SELECT * FROM dny_news_letta_utente WHERE id_utente_newsletter=%s AND data_notifica_inviata=%s",
					GetSQLValueString($row_RecordsetUtent["id"], "int"),
					GetSQLValueString(data_a_database(date("d/m/Y")),"date"));
				$RecordsetNewsUt = mysqli_query($std_conn, $querystrana) or die(mysqli_error($std_conn));
				$row_RecordsetNewsUt = mysqli_fetch_assoc($RecordsetNewsUt);
				$totalRows_RecordsetNewsUt = mysqli_num_rows($RecordsetNewsUt);
				$narts = 0;
				$mailconarticoli = false;
				if($totalRows_RecordsetNewsUt>0){
					$corpomail .= "<p>Elenco nuovi articoli:<br />
								   <table cellpadding='2' cellspacing='2'>
									  <tr>
										<td>Categoria</td>
										<td>Oggetto</td>
										<td>Link</td>
										<td>Procedura</td>
									  </tr>";
					do{
						mysqli_select_db($std_conn, $database_std_conn);
						$query_Recordset1 = sprintf("SELECT A.*, B.nome, C.nome Sottocategoria, D.nome Categoria
						FROM (((( (dny_news as A LEFT JOIN dny_news_lingua as B ON A.id = B.id_ref) 
						LEFT JOIN dny_sottocategoria ON A.id_sottocategoria = dny_sottocategoria.id )
						LEFT JOIN dny_sottocategoria_lingua as C ON dny_sottocategoria.id = C.id_ref)
						LEFT JOIN dny_categoria ON dny_sottocategoria.id_categoria = dny_categoria.id )
						LEFT JOIN dny_categoria_lingua as D ON dny_categoria.id = D.id_ref)			
						WHERE A.deleted=0 AND (A.segnalato_newsletter=0)
										  AND (B.id_lingua=%s OR B.id_lingua is Null) 
										  AND (C.id_lingua=%s OR C.id_lingua is Null) 
										  AND (D.id_lingua=%s OR D.id_lingua is Null) 
										  AND A.is_attivo=1 AND A.id=%s", 
											GetSQLValueString($_SESSION["linguadefault"], "int"),
											GetSQLValueString($_SESSION["linguadefault"], "int"),
											GetSQLValueString($_SESSION["linguadefault"], "int"),
											GetSQLValueString($row_RecordsetNewsUt["id_news"], "int"));
			
						$Recordset1 = mysqli_query($std_conn, $query_Recordset1) or die(mysqli_error($std_conn));
						if($row_Recordset1 = mysqli_fetch_assoc($Recordset1)){
							if($row_Recordset1["is_ristretta_negoziata"]!="0"){
								$mailconarticoli = true;

								$tipoprocedura = ($row_Recordset1["is_ristretta_negoziata"]=="0")?"Standard":$row_Recordset1["tipo_procedura"];
								$linkgara = $_SESSION["globalCompleteUrl"]."/area-riservata-articolo/?ida=".$row_Recordset1["id"];
								$corpomail .= sprintf("
									<tr>
										<td>%s</td>
										<td>%s</td>
										<td><a href='%s' target='_blank'>%s</a></td>
										<td>%s</td>
									</tr>
								",
								htmlentities($row_Recordset1["Categoria"],ENT_QUOTES, "UTF-8")." - ".htmlentities($row_Recordset1["Sottocategoria"],ENT_QUOTES, "UTF-8"),
								htmlentities($row_Recordset1["nome"],ENT_QUOTES, "UTF-8"),
								$linkgara,
								$linkgara,
								$tipoprocedura);
				
								$narts++;
							}
						}
						mysqli_free_result($Recordset1);
					}while($row_RecordsetNewsUt = mysqli_fetch_assoc($RecordsetNewsUt)); 
					$corpomail .= "</table></p>";
				}
				mysqli_free_result($RecordsetNewsUt);
				
				$corpomail .= "</body></html>";
				
				$mail->Body = $corpomail;
				$mail_rn = $corpomail."<p>&nbsp;</p>";
				//echo "<p>&nbsp;</p><p>&nbsp;</p>".$corpomail;
				$mail->AddAddress($row_RecordsetUtent["email"]);
				//Controllo che ci sia almeno una gara per questo utente altrimenti è inutile inviare la mail
				if(($narts>0)&&($mailconarticoli)){
					if (!$mail->send()) {
					    $riepilogo_invii_rn .= '\n<br />Mailer Error: ' . $mail->ErrorInfo;
					} else {
					    $riepilogo_invii_rn .= '\n<br />Message sent!';
					}
					$riepilogo_invii_rn .= "<br />Inviata mail a <strong>".$row_RecordsetUtent["nome"]."</strong> (" . $row_RecordsetUtent["email"] . ") per le categorie: ".$categorie_invio.".";
				}else{
					$riepilogo_invii_rn .= "<br /><strong>NON</strong> Inviata mail a <strong>".$row_RecordsetUtent["nome"]."</strong> (nessun articolo)";
			
				}
				$mail->ClearAddresses();
				/** ***************************************** **/
				/** INVIO MAIL RISTRETTE / NEGOZIATE - FINE   **/
				/** ***************************************** **/				
								
			}


    	}while($row_RecordsetUtent = mysqli_fetch_assoc($RecordsetUtent)); 
    }
    mysqli_free_result($RecordsetUtent);	
	
	unset($mail);
	
	$riepilogo_invii = $riepilogo_invii_std .$mail_std . $riepilogo_invii_rn . $mail_rn;

	


	/* *** */
	// Vado a spuntare come "inviati" gli articoli delle categorie selezionate
	/* *** */
	//categorie
	mysqli_select_db($std_conn, $database_std_conn);
	$query_RecordsetCat = sprintf("SELECT A.*, B.nome FROM (dny_categoria as A LEFT JOIN dny_categoria_lingua as B ON A.id = B.id_ref) WHERE A.deleted=0 AND A.id_linea=1 AND (B.id_lingua=%s OR B.id_lingua is Null) ORDER BY A.ordinamento ASC", 
						GetSQLValueString($_SESSION["linguadefault"],"int"));
	$RecordsetCat = mysqli_query($std_conn, $query_RecordsetCat) or die(mysqli_error($std_conn));
	$row_RecordsetCat = mysqli_fetch_assoc($RecordsetCat);
	$totalRows_RecordsetCat = mysqli_num_rows($RecordsetCat);
	if($totalRows_RecordsetCat>0){
		do{
			$idcat = $row_RecordsetCat["id"];
			if(isset($_POST["cat_".$idcat]) && ($_POST["cat_".$idcat]=="1")){
											
				mysqli_select_db($std_conn, $database_std_conn);
				$query_RecordsetSottoCat = sprintf("SELECT * FROM dny_sottocategoria WHERE deleted=0 AND is_attivo=1 AND id_categoria=%s", 
					GetSQLValueString($idcat,"int"));
				$RecordsetSottoCat = mysqli_query($std_conn, $query_RecordsetSottoCat) or die(mysqli_error($std_conn));
				$row_RecordsetSottoCat = mysqli_fetch_assoc($RecordsetSottoCat);
				$totalRows_RecordsetSottoCat = mysqli_num_rows($RecordsetSottoCat);
				if($totalRows_RecordsetSottoCat>0){
					do{
						$idsubcat = $row_RecordsetSottoCat["id"];
						// segno come inviata						
						mysqli_select_db($std_conn, $database_std_conn);
						$query_RecordsetUpdate = sprintf("UPDATE dny_news SET segnalato_newsletter=1 WHERE deleted=0 AND is_attivo=1 AND id_sottocategoria=%s",
							GetSQLValueString($idsubcat,"int"));
						$RecordsetUteCat = mysqli_query($std_conn, $query_RecordsetUpdate) or die(mysqli_error($std_conn));						
						
					}while($row_RecordsetSottoCat = mysqli_fetch_assoc($RecordsetSottoCat)); 
				}
				mysqli_free_result($RecordsetSottoCat);							
			}
		}while($row_RecordsetCat = mysqli_fetch_assoc($RecordsetCat)); 
	}
	mysqli_free_result($RecordsetCat);	
	/* *** FINE */
	// Vado a spuntare come "inviati" gli articoli delle categorie selezionate 
	/* *** FINE */



}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"> 
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title><?php echo $_SESSION["www_title"]; ?> - Invio Completato</title>
		<?php require_once("header.php"); ?>
    <script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
        <script src="../SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
        <script src="../SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
        <link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />


    <link href="../SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
    <link href="../SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
    
</head>
  
	<body><div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->
		
		<?php require_once("sidebar_messages.php"); ?>
		
		<div id="insert-content"> <!-- Main Content Section with everything -->
			
            <?php require_once("top_bar.php"); ?>

			
			<div class="content-box"><!-- Start Content Box -->
				
				<div class="content-box-header">
					
					<h3>Invio Completato</h3>
					
					<ul class="content-box-tabs">
						<li><a href="#tab1" class="default-tab">Invio Completato</a></li>
					</ul>
					
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
					
					<div class="tab-content default-tab" id="tab1"> <!-- This is the target div. id must match the href of this div's tab -->
   
<?php if($riepilogo_invii!=""){ ?>
	<p><strong>Esito invii:</strong><br />
	<?php echo $riepilogo_invii; ?></p>						
<?php }else{ ?>
	<p><strong>Esito invii:</strong></p>
	<p>Nessun invio eseguito. <br />
Assicurarsi di aver selezionato almeno una categoria e di aver inserito e configurato correttamente gli utenti.</p>
<?php } ?>

						
					</div> <!-- End #tab1 -->
					
        
					
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->
			

			
            <?php require_once("footer.php"); ?>

			
		</div> <!-- End #insert-content -->
		
	</div>

	</body>
  
</html>
