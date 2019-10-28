<?php 
require_once('../Connections/std_conn.php');
require_once('../funzioni.php');

$MM_authorizedUsers = "1,2,3";
require_once("restrict.php");

$sufx_sezione="nazioni";
$tabella = "dny_nazione";

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if($_POST["nomeform"]=="vv"){
	
	//Recupero il prossimo num ordinamento
	mysqli_select_db($std_conn, $database_std_conn);
	$query_nextOrdinamento = sprintf("SELECT MAX(ordinamento) massimo FROM %s",$tabella);
	$nextOrdinamento = mysqli_query($std_conn, $query_nextOrdinamento) or die(mysqli_error($std_conn));
	$row_nextOrdinamento = mysqli_fetch_assoc($nextOrdinamento);
	$totalRows_nextOrdinamento = mysqli_num_rows($nextOrdinamento);
	if($totalRows_nextOrdinamento>0){
		$ordinamento = $row_nextOrdinamento["massimo"] + 1;
	}else{
		$ordinamento = 1;
	}
	mysqli_free_result($nextOrdinamento);
		
	$applica_iva = ($_POST['applica_iva']=="1")?1:0;
	$insertSQL = sprintf("INSERT INTO %s (nome, codice_nazione, applica_iva, id_gruppo_spedizione, ordinamento) VALUES (%s, %s, %s, %s, %s)",
			   $tabella,
			   GetSQLValueString($_POST['nome'], "text"),
			   GetSQLValueString($_POST['codice_nazione'], "text"),
			   GetSQLValueString($applica_iva, "int"),
			   GetSQLValueString($_POST['id_gruppo_spedizione'], "int"),
			   GetSQLValueString($ordinamento, "int"));

	mysqli_select_db($std_conn, $database_std_conn);
	$Result1 = mysqli_query($std_conn, $insertSQL) or die(mysqli_error($std_conn));
	$id_inserito = mysqli_insert_id($std_conn);
	
	logThis($sufx_sezione, "Aggiunto", $id_inserito);
	header("Location: ".$sufx_sezione."_gest.php");

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"> 
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title><?php echo $_SESSION["www_title"]; ?> - Aggiungi nazione</title>
		<?php require_once("header.php"); ?>
        <script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
	    <link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />


</head>
  
	<body><div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->
		
		<?php require_once("sidebar_messages.php"); ?>
		
		<div id="insert-content"> <!-- Main Content Section with everything -->
			
            <?php require_once("top_bar.php"); ?>

			
			<div class="content-box"><!-- Start Content Box -->
				
				<div class="content-box-header">
					
					<h3>Aggiunta nazione</h3>
					
					<ul class="content-box-tabs">
						<li><a href="#tab1" class="default-tab">Aggiunta nazione</a></li>
					</ul>
					
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
					
					<div class="tab-content default-tab" id="tab1"> <!-- This is the target div. id must match the href of this div's tab -->
                    
						
					  <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data">
       	<input type="hidden" name="nomeform" value="vv" />
							
							<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
								
								
							  <p>
								<label>Nome Nazione</label>
								<span id="sprytextfield1">
								<input class="text-input small-input" type="text" id="nome" name="nome" />
								<span class="textfieldRequiredMsg input-notification error png_bg">È obbligatorio specificare un valore.</span></span>
                                <br /><small>Es: Italia</small>
							  </p>		

							  <p>
								<label>Codice Nazione</label>
								<span id="sprytextfield2">
								<input class="text-input small-input" type="text" id="codice_nazione" name="codice_nazione" />
								<span class="textfieldRequiredMsg input-notification error png_bg">È obbligatorio specificare un valore.</span></span>
                                <br /><small>Es: IT</small>
							  </p>		
                              
                              <p>
                                <label>Gruppo di Spedizione</label>
								
                                <select name="id_gruppo_spedizione" id="id_gruppo_spedizione">
<?php
mysqli_select_db($std_conn, $database_std_conn);
$tabella = "dny_gruppo_spedizione";
$query_rsc = sprintf("SELECT id, nome FROM %s WHERE deleted=0 ORDER BY ordinamento ASC", $tabella);
$rsc = mysqli_query($std_conn, $query_rsc) or die(mysqli_error($std_conn));
if($row_rsc = mysqli_fetch_assoc($rsc)){
	do{
		printf('<option value="%s">%s</option>',$row_rsc["id"],$row_rsc["nome"]);
	}while($row_rsc = mysqli_fetch_assoc($rsc));
}
mysqli_free_result($rsc);
?>
                                </select>								
                                <br /><small>Selezionare il gruppo per applicare i costi</small>
                              </p>	  
                              
                              <p>
                              <input type="checkbox" name="applica_iva" id="applica_iva" value="1" checked="checked" /> Applica IVA ai privati
                              <br /><small>Selezionando questa voce si applica l'Iva in fattura ai privati.</small>
                              </p>                            
							
								
								<p>
									<input class="button" type="submit" value="Inserisci" />
								</p>
								
							</fieldset>
							
							<div class="clear"></div><!-- End .clear -->
							
						</form>
						
					</div> <!-- End #tab1 -->
					
        
					
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->
			

			
            <?php require_once("footer.php"); ?>

			
		</div> <!-- End #insert-content -->
		
	</div>
	<script type="text/javascript">
<!--
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
//-->
    </script>
	</body>
  
</html>
