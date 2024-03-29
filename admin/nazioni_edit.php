<?php 
require_once('../Connections/std_conn.php');
require_once('../funzioni.php');

$MM_authorizedUsers = "1,2,3";
require_once("restrict.php");

$sufx_sezione="nazioni";
$tabella = "dny_nazione";

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

if($_POST["nomeform"]=="vv"){
	$applica_iva = ($_POST['applica_iva']=="1")?1:0;
	$insertSQL = sprintf("UPDATE %s SET 
						 nome=%s, 
						 codice_nazione=%s, 
						 applica_iva=%s,
						 id_gruppo_spedizione=%s
						 WHERE id=%s",
			   $tabella,
			   GetSQLValueString($_POST['nome'], "text"),
			   GetSQLValueString($_POST['codice_nazione'], "text"),
			   GetSQLValueString($applica_iva, "int"),
			   GetSQLValueString($_POST['id_gruppo_spedizione'], "int"),
			   GetSQLValueString($_POST['id'], "int"));

	mysqli_select_db($std_conn, $database_std_conn);
	$Result1 = mysqli_query($std_conn, $insertSQL) or die(mysqli_error($std_conn));
	$id_inserito = $_POST['id'];
	
$stringa_errori_file = "";
$uploaddir = $_SESSION["path_upload_admin"];


logThis($sufx_sezione, "Modificato", $id_inserito);
header("Location: ".$sufx_sezione."_gest.php");

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"> 
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title><?php echo $_SESSION["www_title"]; ?> - Modifica nazione</title>
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
					
					<h3>Modifica nazione</h3>
					
					<ul class="content-box-tabs">
						<li><a href="#tab1" class="default-tab">Modifica nazione</a></li>
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
								<label>Nome Nazione</label>
								<span id="sprytextfield1">
								<input class="text-input small-input" type="text" id="nome" name="nome" value="<?php echo $row_Recordset1["nome"]; ?>" />
								<span class="textfieldRequiredMsg input-notification error png_bg">� obbligatorio specificare un valore.</span></span>
                                <br /><small>Es: Italia</small>
							  </p>
                              
                              <p>
								<label>Codice Nazione</label>
								<span id="sprytextfield2">
								<input class="text-input small-input" type="text" id="codice_nazione" name="codice_nazione" value="<?=$row_Recordset1["codice_nazione"]?>" />
								<span class="textfieldRequiredMsg input-notification error png_bg">� obbligatorio specificare un valore.</span></span>
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
		if($row_rsc["id"]==$row_Recordset1["id_gruppo_spedizione"]){
			$sel = ' selected="selected" ';			
		}else{
			$sel = '';
		}
		printf('<option value="%s" %s>%s</option>', $row_rsc["id"], $sel, $row_rsc["nome"]);
	}while($row_rsc = mysqli_fetch_assoc($rsc));
}
mysqli_free_result($rsc);
?>
                                </select>								
                                <br /><small>Selezionare il gruppo per applicare i costi</small>
                              </p>	  

                              <p>
                              <input type="checkbox" name="applica_iva" id="applica_iva" value="1" <?php if($row_Recordset1["applica_iva"]=="1") { echo 'checked="checked"'; } ?> /> Applica IVA ai privati
                              <br /><small>Selezionando questa voce si applica l'Iva in fattura ai privati.</small>
                              </p>                            

                             	
								<p>
									<input class="button" type="submit" value="Aggiorna" />&nbsp;&nbsp;<input class="button" type="button" value="Chiudi" onclick="javascript:location.href='<?php echo $sufx_sezione; ?>_gest.php';" />
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
<?php
mysqli_free_result($Recordset1);
?>
