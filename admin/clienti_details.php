<?php 
require_once('../Connections/std_conn.php');
require_once('../funzioni.php');

$MM_authorizedUsers = "1,2";
require_once("restrict.php");

$sufx_sezione="clienti";
$tabella = "dny_cliente";

$colname_Recordset1 = "-1";
if (isset($_GET['id'])) {
  $colname_Recordset1 = $_GET['id'];
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


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"> 
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title><?php echo $_SESSION["www_title"]; ?> - Informazioni Cliente</title>
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
					
					<h3>Informazioni Cliente</h3>
					
					<ul class="content-box-tabs">
						<li><a href="#tab1" class="default-tab">Informazioni Cliente</a></li>
					</ul>
					
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
					
					<div class="tab-content default-tab" id="tab1"> <!-- This is the target div. id must match the href of this div's tab -->
                    
							<?php
								// Recupero Nazione e Provincia di Fatturazione e di Recapito
								$id_nazione = $row_Recordset1["id_nazione"];
								$id_provincia = $row_Recordset1["id_provincia"];
								$id_nazione_sped = $row_Recordset1["id_nazione_sped"];
								$id_provincia_sped = $row_Recordset1["id_provincia_sped"];
								
								//nazione
								mysqli_select_db($std_conn, $database_std_conn);
								$query_rs_det = sprintf("SELECT * FROM dny_nazione WHERE id = %s", GetSQLValueString((int)$id_nazione, "int"));
								$rs_det = mysqli_query($std_conn, $query_rs_det) or die(mysqli_error($std_conn));
								if($row_rs_det = mysqli_fetch_assoc($rs_det))
									$id_nazione = $row_rs_det["nome"];
								mysqli_free_result($rs_det);
								//provincia
								mysqli_select_db($std_conn, $database_std_conn);
								$query_rs_det = sprintf("SELECT * FROM dny_provincia WHERE id = %s", GetSQLValueString((int)$id_provincia, "int"));
								$rs_det = mysqli_query($std_conn, $query_rs_det) or die(mysqli_error($std_conn));
								if($row_rs_det = mysqli_fetch_assoc($rs_det))
									$id_provincia = $row_rs_det["nome"];
								mysqli_free_result($rs_det);
								//nazione
								mysqli_select_db($std_conn, $database_std_conn);
								$query_rs_det = sprintf("SELECT * FROM dny_nazione WHERE id = %s", GetSQLValueString((int)$id_nazione_sped, "int"));
								$rs_det = mysqli_query($std_conn, $query_rs_det) or die(mysqli_error($std_conn));
								if($row_rs_det = mysqli_fetch_assoc($rs_det))
									$id_nazione_sped = $row_rs_det["nome"];
								mysqli_free_result($rs_det);
								//provincia
								mysqli_select_db($std_conn, $database_std_conn);
								$query_rs_det = sprintf("SELECT * FROM dny_provincia WHERE id = %s", GetSQLValueString((int)$id_provincia_sped, "int"));
								$rs_det = mysqli_query($std_conn, $query_rs_det) or die(mysqli_error($std_conn));
								if($row_rs_det = mysqli_fetch_assoc($rs_det))
									$id_provincia_sped = $row_rs_det["nome"];
								mysqli_free_result($rs_det);
							?>
								
							  <p>
                              	<strong>Nome:</strong> <?php echo $row_Recordset1["nome"]; ?><br />
								<strong>Cognome:</strong> <?php echo $row_Recordset1["cognome"]; ?><br />
								<strong>Email:</strong> <?php echo $row_Recordset1["email"]; ?><br />
								<strong>Nazione:</strong> <?php echo $id_nazione; ?><br />
								<strong>Provincia:</strong> <?php echo $id_provincia; ?><br />
								<strong>Citt&agrave;:</strong> <?php echo $row_Recordset1["citta"]; ?><br />
								<strong>CAP:</strong> <?php echo $row_Recordset1["cap"]; ?><br />
								<strong>Indirizzo:</strong> <?php echo $row_Recordset1["indirizzo1"]; ?><br />
								<strong>Indirizzo:</strong> <?php echo $row_Recordset1["indirizzo2"]; ?><br />
								<strong>Telefono:</strong> <?php echo $row_Recordset1["telefono"]; ?>
                              </p>
                              <p>
								<strong>C. Fiscale:</strong> <?php echo $row_Recordset1["cf_piva"]; ?><br />
								<strong>P. Iva:</strong> <?php echo $row_Recordset1["partitaiva"]; ?>
                              </p>
                              <p>
                              	<strong>Recapito Spedizione dell'ultimo ordine effettuato</strong><br />
								<strong>Persona di riferimento:</strong> <?php echo $row_Recordset1["riferimento_sped"]; ?><br />
								<strong>Presso:</strong> <?php echo $row_Recordset1["presso_sped"]; ?><br />
								<strong>Nazione:</strong> <?php echo $id_nazione_sped; ?><br />
								<strong>Provincia:</strong> <?php echo $id_provincia_sped; ?><br />
								<strong>Telefono:</strong> <?php echo $row_Recordset1["telefono_sped"]; ?><br />
								<strong>Citt&agrave;:</strong> <?php echo $row_Recordset1["citta_sped"]; ?><br />
								<strong>CAP:</strong> <?php echo $row_Recordset1["cap_sped"]; ?><br />
								<strong>Indirizzo:</strong> <?php echo $row_Recordset1["indirizzo1_sped"]; ?><br />
								<strong>Indirizzo:</strong> <?php echo $row_Recordset1["indirizzo2_sped"]; ?>
                              </p>
                              <p>
                              	<strong>Newsletter:</strong> <?php echo ($row_Recordset1['acconsento_newsletter']=="1")?"S&igrave;":"No"; ?>
                              </p>
                              
                              <?php
								//elenco ordini del cliente
								mysqli_select_db($std_conn, $database_std_conn);
								$query_rs_det = sprintf("SELECT * FROM dny_ordine WHERE id_cliente = %s", GetSQLValueString($row_Recordset1["id"], "int"));
								$rs_det = mysqli_query($std_conn, $query_rs_det) or die(mysqli_error($std_conn));
								if($row_rs_det = mysqli_fetch_assoc($rs_det)){
									echo "<p><strong>Riepilogo ordini di questo cliente</strong><br />";
									do{
										switch($row_rs_det['id_stato_ordine']){
											case 1: $metodo = "Abbandonato"; break;
											case 2: $metodo = "Attesa Pagamento"; break;
											case 3: $metodo = "Richiesta inviata"; break;
											case 4: $metodo = "PAGATO"; break;
											case 5: $metodo = "Preso in carico"; break;
											case 6: $metodo = "Spedito"; break;
											case 7: $metodo = "Concluso"; break;
											case 8: $metodo = "Annullato"; break;
											case 9: $metodo = "Eliminato"; break;
										}
										switch($row_rs_det['metodo']){
											case 1: $stato = "PayPal"; break;
											case 2: $stato = "UNICREDIT"; break;
											case 3: $stato = "Bonifico"; break;
											case 4: $stato = "Contrassegno"; break;
										}
										$data_ordine = data_a_video($row_rs_det['created'],1);
										
										printf("<strong>%s</strong> - %s | %s - %s - &euro; %s | <a href='ordini_edit.php?id=%s'>vedi ordine</a><br />",
											$row_rs_det["numero_ordine"],
											$data_ordine,
											$metodo,
											$stato,
											$row_rs_det["totale"],
											$row_rs_det["id"]);
									}while($row_rs_det = mysqli_fetch_assoc($rs_det));	
									echo "</p>";
								}
								mysqli_free_result($rs_det);	
							  ?>

							  <p><input class="button" type="button" value="Chiudi" onclick="javascript:location.href='<?php echo $sufx_sezione; ?>_gest.php';" /></p>
                                
						
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
