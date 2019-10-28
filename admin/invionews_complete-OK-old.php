<?php 
require_once('../Connections/std_conn.php');
require_once('../funzioni.php');

$MM_authorizedUsers = "1,2";
require_once("restrict.php");

$riepilogo_invii = "";

if($_POST["nomeform"]=="vv"){

	//New PhpMailer
	include("../PHPMailer-master/config_consorzio.php");

	$mail->Subject	= $_SESSION["www_title"]." - nuovi articoli nell'area riservata del sito.";
	
	
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
	$riepilogo_invii .= "<br />Inviata mail a <strong>".$row_RecordsetUtent["nome"]."</strong> (" . $row_RecordsetUtent["email"] . ") per le categorie: ".$categorie_invio.".";
	//Email al cliente
	$mail->Body = sprintf("<html><body>
	<p>Salve %s,<br /><br />
	sono presenti nuovi articoli sul sito %s per le categorie: %s.<br /><br />
	<a href='%s'>Collegati al sito</a> e controlla subito le novit&agrave;.</p>
	</body></html>",
	$row_RecordsetUtent["nome"],
	$_SESSION["www_title"],
	$categorie_invio,
	$_SESSION["globalCompleteUrl"]."/area-riservata/?mail=".$row_RecordsetUtent["email"]);
	
	$mail->AddAddress($row_RecordsetUtent["email"]);
	$mail->Send();
	$mail->ClearAddresses();				
					
}

    	}while($row_RecordsetUtent = mysqli_fetch_assoc($RecordsetUtent)); 
    }
    mysqli_free_result($RecordsetUtent);	
	
	unset($mail);
	
	
	// Una volta terminati gli invii vado a spuntare come "inviati" gli articoli delle categorie selezionate
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
	$query_ciclo_articoli = sprintf("SELECT * FROM dny_news WHERE deleted=0 AND is_attivo=1 AND segnalato_newsletter=0 AND richiedi_interesse=1 AND id_sottocategoria=%s", 
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
	
						// Devo inserire le richieste di conferma - FINE

						mysqli_select_db($std_conn, $database_std_conn);
						$query_RecordsetUpdate = sprintf("UPDATE dny_news SET segnalato_newsletter=1 WHERE deleted=0 AND is_attivo=1 AND id_sottocategoria=%s",
							GetSQLValueString($idsubcat,"int"));
						$RecordsetUteCat = mysqli_query($std_conn, $query_RecordsetUpdate) or die(mysqli_error($std_conn));						
						
					}while($row_RecordsetSottoCat = mysqli_fetch_assoc($RecordsetSottoCat)); 
				}
				mysqli_free_result($RecordsetSottoCat);
							
//ciclo utenti
mysqli_free_result($Recordset_ciclo_utenti);
				
			}

		}while($row_RecordsetCat = mysqli_fetch_assoc($RecordsetCat)); 
	}
	mysqli_free_result($RecordsetCat);
	

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
