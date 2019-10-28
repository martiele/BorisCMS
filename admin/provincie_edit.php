<?php 
require_once('../Connections/std_conn.php');
require_once('../funzioni.php');

$MM_authorizedUsers = "1,2,3";
require_once("restrict.php");

$sufx_sezione = "provincie";
$tabella = "dny_provincia";
$sufx_sezione_padre="nazioni";
$tabella_padre = "dny_nazione";
$label_id_padre = "id_nazione";
$padre_get_post = "nazione";
$id_padre=0;
if( isset($_POST[$padre_get_post]) && ($_POST[$padre_get_post]>0) ){
	$id_padre = $_POST[$padre_get_post];
}else if( isset($_GET[$padre_get_post]) && ($_GET[$padre_get_post]>0) ){
	$id_padre = $_GET[$padre_get_post];
}
if($id_padre<=0) die();


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
						 nome=%s, 
						 codice_provincia=%s, 
						 id_gruppo_spedizione=%s
						 WHERE id=%s",
			   $tabella,
			   GetSQLValueString($_POST['nome'], "text"),
			   GetSQLValueString($_POST['codice_provincia'], "text"),
			   GetSQLValueString($_POST['id_gruppo_spedizione'], "int"),
			   GetSQLValueString($_POST['id'], "int"));

	mysqli_select_db($std_conn, $database_std_conn);
	$Result1 = mysqli_query($std_conn, $insertSQL) or die(mysqli_error($std_conn));
	$id_inserito = $_POST['id'];


$stringa_errori_file = "";
$uploaddir = $_SESSION["path_upload_admin"];


logThis($sufx_sezione, "Modificato", $id_inserito);
header("Location: ".$sufx_sezione."_gest.php?".$padre_get_post."=".$id_padre);

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"> 
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title><?php echo $_SESSION["www_title"]; ?> - Modifica soglia Prezzo/Peso</title>
		<?php require_once("header.php"); ?>

        <script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
	    <link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />


</head>
  
	<body><div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->
		
		<?php require_once("sidebar_messages.php"); ?>
		
		<div id="insert-content"> <!-- Main Content Section with everything -->
			
            <?php require_once("top_bar.php"); ?>
            
			<?php 
				require_once("breadcrump3.php");
				//passo l'id categoria
				breadcrump($sufx_sezione,"edit",$id_padre);
			?>

	        <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="nomeform" value="vv" />
                <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>" />
				<input type="hidden" name="<?=$padre_get_post?>" value="<?=$id_padre?>" />                
			<div class="content-box"><!-- Start Content Box -->
				
				<div class="content-box-header">
					
					<h3>Modifica soglia Prezzo/Peso</h3>
                    <ul class="content-box-tabs">
						<li><a href="#tab1" class="default-tab">Modifica soglia Prezzo/Peso</a></li>
					</ul>
				  <div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
					
				<div class="content-box-content">
					<div class="tab-content default-tab" id="tab1"> <!-- This is the target div. id must match the href of this div's tab -->

						
						
							<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
							
                            
                              <p>
								<label>Nome Provincia</label>
								<span id="sprytextfield1">
								<input class="text-input small-input" type="text" id="nome" name="nome" value="<?=$row_Recordset1["nome"]?>" />
								<span class="textfieldRequiredMsg input-notification error png_bg">È obbligatorio specificare un valore.</span></span>
                                <br /><small>Es: Firenze</small>
							  </p>		

							  <p>
								<label>Codice Provincia</label>
								<span id="sprytextfield2">
								<input class="text-input small-input" type="text" id="codice_provincia" name="codice_provincia" value="<?=$row_Recordset1["codice_provincia"]?>" />
								<span class="textfieldRequiredMsg input-notification error png_bg">È obbligatorio specificare un valore.</span></span>
                                <br /><small>Es: FI</small>
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
                                <input class="button" type="submit" value="Aggiorna" />&nbsp;&nbsp;<input class="button" type="button" value="Chiudi" onclick="javascript:location.href='<?php echo $sufx_sezione; ?>_gest.php?<?=$padre_get_post?>=<?=$id_padre?>';" />
                            </p>
                                
    
							</fieldset>
							
							<div class="clear"></div><!-- End .clear -->
												
					</div> <!-- End #tabX -->
					
					
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->
			
            
             </form>			

	
			
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
