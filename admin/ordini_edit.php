<?php 
require_once('../Connections/std_conn.php');
require_once('../funzioni.php');
require_once('../traduzioni.php');

$MM_authorizedUsers = "1,2";
require_once("restrict.php");

$sufx_sezione="ordini";
$tabella = "dny_ordine";

$colname_Recordset1 = "-1";
if (isset($_GET['id'])) {
  $colname_Recordset1 = $_GET['id'];
}


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if($_POST["nomeform"]=="vv"){
	
	if($_POST['id_stato_ordine']!=$_POST['id_stato_ordine_attuale']){ //C'è stata effettivamente una modifica
	
		$insertSQL = sprintf("UPDATE %s SET 
							 id_stato_ordine=%s
							 WHERE id=%s",
				   $tabella,
				   GetSQLValueString($_POST['id_stato_ordine'], "int"),
				   GetSQLValueString($_POST['id'], "int"));
		mysqli_select_db($std_conn, $database_std_conn);
		$Result1 = mysqli_query($std_conn, $insertSQL) or die(mysqli_error($std_conn));
		$id_inserito = $_POST['id'];
		
		if($_POST['id_stato_ordine']=="4"){ 
			// Se ho segnato l'ordine come PAGATO vado ad aggiornare il magazzino (nel caso di Bonifico o Contrassegno) altrimenti lo troverò già a posto e salto questa parte.
			$numeroOrdine = $_POST['id'];
			//Controllo di non aver già scalato i prodotti precedentemente
			$da_scalare = true;
			$selectSQL = sprintf("SELECT qta_scalate FROM dny_ordine WHERE id = %s", GetSQLValueString($numeroOrdine, "int"));
			mysqli_select_db($std_conn, $database_std_conn);
			$rec_sel = mysqli_query($std_conn, $selectSQL) or die(mysqli_error($std_conn));			
			if($row_rec_sel = mysqli_fetch_assoc($rec_sel)){
				if($row_rec_sel["qta_scalate"]=="1"){
					$da_scalare = false;
				}
			}
			mysqli_free_result($rec_sel);
			if($da_scalare){
				$selectSQL = sprintf("SELECT dny_ordine_dettaglio.qta qtacarrello, dny_ordine_dettaglio.id_prodotto, dny_ordine_dettaglio.id_variante, dny_taglia_colore.qta qtamagazzino FROM dny_ordine_dettaglio JOIN dny_taglia_colore ON (dny_ordine_dettaglio.id_variante = dny_taglia_colore.id AND dny_ordine_dettaglio.id_prodotto = dny_taglia_colore.id_prodotto) WHERE id_ordine = %s", GetSQLValueString($numeroOrdine, "int"));
				mysqli_select_db($std_conn, $database_std_conn);
				$rec_sel = mysqli_query($std_conn, $selectSQL) or die(mysqli_error($std_conn));			
				$row_rec_sel = mysqli_fetch_assoc($rec_sel);
				$totalRows_rec_sel = mysqli_num_rows($rec_sel);
				if($totalRows_rec_sel>0){
					do{
						//Devo cercare la quantità attuale e scalarla
						$newQta = ((int)$row_rec_sel["qtamagazzino"])-((int)$row_rec_sel["qtacarrello"]);
						$newQta = ($newQta>=0)?$newQta:0;
						$insertSQL = sprintf("UPDATE dny_taglia_colore SET qta=%s WHERE id=%s AND id_prodotto=%s LIMIT 1 ;",
							GetSQLValueString($newQta, "int"),
							GetSQLValueString($row_rec_sel["id_variante"],"int"),
							GetSQLValueString($row_rec_sel["id_prodotto"],"int"));
						mysqli_select_db($std_conn, $database_std_conn);
						$Result1 = mysqli_query($std_conn, $insertSQL) or die(mysqli_error($std_conn));
					}while($row_rec_sel = mysqli_fetch_assoc($rec_sel));
				}
				mysqli_free_result($rec_sel);
				// segno di aver scalato le qta dell'ordine
				$insertSQL = sprintf("UPDATE dny_ordine SET qta_scalate=1 WHERE id=%s LIMIT 1;",
					GetSQLValueString($numeroOrdine,"int"));
				mysqli_select_db($std_conn, $database_std_conn);
				$Result1 = mysqli_query($std_conn, $insertSQL) or die(mysqli_error($std_conn));
			}
			// Fine aggiornamento Magazzino			
		}


		if($_POST['notifica']=="1"){ //devo notificare la variazione al cliente
			$lingua_mail = $_POST["id_lingua_mail"];
			$numero_ordine = $_POST["numero_ordine"];
			$email_cliente = $_POST["email"];
			$riepilogo_mail = file_get_contents(traduci("./mail_cambiostato.html",$lingua_mail));
			$riepilogo_mail = str_replace("[[id_ordine]]",$numero_ordine,$riepilogo_mail);
			switch($_POST['id_stato_ordine']){
				case 1: $stringa_ordine =  "Abbandonato"; break;
				case 2: $stringa_ordine =  "Attesa Pagamento"; break;
				case 3: $stringa_ordine =  "Richiesta inviata"; break;
				case 4: $stringa_ordine =  "PAGATO"; break;
				case 5: $stringa_ordine =  "Preso in carico"; break;
				case 6: $stringa_ordine =  "Spedito"; break;
				case 7: $stringa_ordine =  "Concluso"; break;
				case 8: $stringa_ordine =  "Annullato"; break;
				case 9: $stringa_ordine =  "Eliminato"; break;
			}
			$riepilogo_mail = str_replace("[[stato_ordine]]", traduci($stringa_ordine,$lingua_mail), $riepilogo_mail);
		

			//New PhpMailer
			include("../PHPMailer-master/config_consorzio.php");

			//Email al cliente
			$mail->Subject	= $_SESSION["www_title"]." - ".traduci("Aggiornamento stato dell'ordine. Ordine")." #".$numero_ordine;
			$mail->Body = $riepilogo_mail;
			$mail->AddAddress($email_cliente);
			$mail->Send();
			$mail->ClearAddresses();
			
		}

		logThis($sufx_sezione, "Modificato", $id_inserito);
	}
	header("Location: ".$sufx_sezione."_gest.php");
}

mysqli_select_db($std_conn, $database_std_conn);
$query_Recordset1 = sprintf("SELECT * FROM %s WHERE id = %s", 
					$tabella,
					GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysqli_query($std_conn, $query_Recordset1) or die(mysqli_error($std_conn));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
if($totalRows_Recordset1==0)
	die();


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"> 
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title><?php echo $_SESSION["www_title"]; ?> - Gestione Ordine #<?php echo $row_Recordset1["numero_ordine"]; ?></title>
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
					
					<h3>Gestione Ordine #<?php echo $row_Recordset1["numero_ordine"]; ?></h3>
					
					<ul class="content-box-tabs">
						<li><a href="#tab1" class="default-tab">Gestione Ordine</a></li>
					</ul>
					
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
					
					<div class="tab-content default-tab" id="tab1"> <!-- This is the target div. id must match the href of this div's tab -->
                    
						
					  <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data">
				<input type="hidden" name="nomeform" value="vv" />
                <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>" />
							
							<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
								
							  <p>
								<label>Stato Ordine</label>
                                <select name="id_stato_ordine">
                                	<option value="1" <?php if(!strcmp($row_Recordset1["id_stato_ordine"],1)){ echo 'selected="selected"'; } ?>>Abbandonato</option>
                                	<option value="2" <?php if(!strcmp($row_Recordset1["id_stato_ordine"],2)){ echo 'selected="selected"'; } ?>>Attesa Pagamento</option>
                                	<option value="3" <?php if(!strcmp($row_Recordset1["id_stato_ordine"],3)){ echo 'selected="selected"'; } ?>>Richiesta inviata</option>
                                	<option value="4" <?php if(!strcmp($row_Recordset1["id_stato_ordine"],4)){ echo 'selected="selected"'; } ?>>PAGATO</option>
                                	<option value="5" <?php if(!strcmp($row_Recordset1["id_stato_ordine"],5)){ echo 'selected="selected"'; } ?>>Preso in carico</option>
                                	<option value="6" <?php if(!strcmp($row_Recordset1["id_stato_ordine"],6)){ echo 'selected="selected"'; } ?>>Spedito</option>
                                	<option value="7" <?php if(!strcmp($row_Recordset1["id_stato_ordine"],7)){ echo 'selected="selected"'; } ?>>Concluso</option>
                                	<option value="8" <?php if(!strcmp($row_Recordset1["id_stato_ordine"],8)){ echo 'selected="selected"'; } ?>>Annullato</option>
                                	<option value="9" <?php if(!strcmp($row_Recordset1["id_stato_ordine"],9)){ echo 'selected="selected"'; } ?>>Eliminato</option>
                                </select>
								<small>Modifica lo stato dell'ordine</small>
							  </p>	

							  <p>
                                <label>Comunica variazione di Stato al cliente</label>
                                <input type="checkbox" name="notifica" value="1" checked="checked" />
                                <small>Selezionando questa casella, nel caso si stia per modificare lo stato dell'ordine, verrà inviata una notifica al cliente.</small>
                              </p>

						      <input type="hidden" name="id_stato_ordine_attuale" id="id_stato_ordine_attuale" value="<?php echo $row_Recordset1["id_stato_ordine"]; ?>" />
						      <input type="hidden" name="id_lingua_mail" value="<?php echo $row_Recordset1["id_lingua"]; ?>" />
						      <input type="hidden" name="nome" value="<?php echo $row_Recordset1["nome"]; ?>" />
						      <input type="hidden" name="cognome" value="<?php echo $row_Recordset1["cognome"]; ?>" />
						      <input type="hidden" name="email" value="<?php echo $row_Recordset1["email"]; ?>" />
					        <input type="hidden" name="numero_ordine" value="<?php echo $row_Recordset1["numero_ordine"]; ?>" />

                            
                             	
								<p>
									<input class="button" type="submit" value="Aggiorna" />&nbsp;&nbsp;<input class="button" type="button" value="Chiudi" onclick="javascript:location.href='<?php echo $sufx_sezione; ?>_gest.php';" />
								</p>
                                
    
							</fieldset>
                            
                            <p>Note relative all'imballaggio:</p>
                            <div style="background-color:#ECB6CA; padding:20px;">
                            	<?php echo $row_Recordset1["note_imballaggio"]; ?>
                            </div>

							<?php if($row_Recordset1["richiedo_fattura"]=="1"){ ?>
                            <p><strong>IL CLIENTE HA RICHIESTO LA FATTURA</strong></p>
                            <?php } ?>

                            <p><strong>L'iva <?php if($row_Recordset1["iva_applicata"]=="0"){ echo 'NON'; } ?> &egrave; stata applicata all'ordine</strong></p>
                            
                            <p>Riepilogo Ordine:</p>
                            <div style="background-color:#CCC; padding:20px;">
                            	<?php echo $row_Recordset1["riepilogo"]; ?>
                            </div>
							
							<div class="clear"></div><!-- End .clear -->
							
						</form>
						
					</div> <!-- End #tab1 -->
					
        
					
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->
			

			
            <?php require_once("footer.php"); ?>

			
		</div> <!-- End #insert-content -->
		
	</div>
    </body>
    
</html>
<?php
mysqli_free_result($Recordset1);
?>
