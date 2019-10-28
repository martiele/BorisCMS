<?php 
require_once('../Connections/std_conn.php');
require_once('../funzioni.php');

$MM_authorizedUsers = "1,2,3";
require_once("restrict.php");

$sufx_sezione="pacco";
$tabella = "dny_pacco";

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
	$insertSQL = sprintf("UPDATE %s SET 
						 nome=%s
						 WHERE id=%s",
			   $tabella,
			   GetSQLValueString($_POST['nome'], "text"),
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
		<title><?php echo $_SESSION["www_title"]; ?> - Modifica tipologia pacco</title>
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
					
					<h3>Modifica tipologia pacco</h3>
					
					<ul class="content-box-tabs">
						<li><a href="#tab1" class="default-tab">Modifica tipologia pacco</a></li>
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
								<label>Denominazione</label>
								<span id="sprytextfield1">
								<input class="text-input small-input" type="text" id="nome" name="nome" value="<?php echo $row_Recordset1["nome"]; ?>" />
								<span class="textfieldRequiredMsg input-notification error png_bg">È obbligatorio specificare un valore.</span></span>
                                <br /><small>Es: Europa</small>
							  </p>	                              


						     <p>
								<label>Lunghezza</label>
								<span id="sprytextfield2">
                                <input class="text-input small-input" type="text" id="lu" name="lu" value="<?php echo $row_Recordset1["lu"]; ?>" />
                                <span class="textfieldRequiredMsg">È obbligatorio specificare un valore.</span><span class="textfieldInvalidFormatMsg">Formato non valido.</span></span></p>		

							  <p>
								<label>Larghezza</label>
								<span id="sprytextfield3">
                                <input class="text-input small-input" type="text" id="la" name="la" value="<?php echo $row_Recordset1["la"]; ?>" />
                                <span class="textfieldRequiredMsg">È obbligatorio specificare un valore.</span><span class="textfieldInvalidFormatMsg">Formato non valido.</span></span></p>		

							  <p>
								<label>Altezza</label>
								<span id="sprytextfield4">
                                <input class="text-input small-input" type="text" id="al" name="al" value="<?php echo $row_Recordset1["al"]; ?>" />
                                <span class="textfieldRequiredMsg">È obbligatorio specificare un valore.</span><span class="textfieldInvalidFormatMsg">Formato non valido.</span></span></p>		
					
                             	
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
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "integer");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "integer");
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "integer");
//-->
    </script>
    </body>
    
</html>
<?php
mysqli_free_result($Recordset1);
?>
