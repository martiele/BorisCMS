<?php 
require_once('../Connections/std_conn.php');
require_once('../funzioni.php');

$MM_authorizedUsers = "1,2";
require_once("restrict.php");

$sufx_sezione="negozianti";
$tabella = "dny_utente";

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
	$nuovapass = trim($_POST['pswd']);
	if($nuovapass!=""){
		$qry_add = sprintf(" pswd=%s, ", GetSQLValueString($nuovapass, "text"));
	}else{
		$qry_add = "";
	}
	$is_attivo = ($_POST['is_attivo']=="1")?1:0;
	$insertSQL = sprintf("UPDATE %s SET 
						 nome=%s,
						 email=%s, 
						 %s
						 level=%s,
						 is_attivo=%s,
						 negozio=%s,
						 telefono=%s,
						 indirizzo=%s,
						 listino=%s
						 WHERE id=%s",
			   $tabella,
			   GetSQLValueString($_POST['nome'], "text"),
			   GetSQLValueString($_POST['email'], "text"),
			   $qry_add,
			   GetSQLValueString($_POST['level'], "int"),
			   GetSQLValueString($is_attivo, "int"),
			   GetSQLValueString($_POST['negozio'], "text"),
			   GetSQLValueString($_POST['telefono'], "text"),
			   GetSQLValueString($_POST['indirizzo'], "text"),
			   GetSQLValueString($_POST['listino'], "int"),
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
		<title><?php echo $_SESSION["www_title"]; ?> - Modifica Negoziante</title>
		<?php require_once("header.php"); ?>

        <script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
        <script src="../SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
        <script src="../SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
        <link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
    <link href="../SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
    <link href="../SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript"><!--//
	
	<?php if($row_Recordset1["level"]!=4){ ?>
	$(document).ready(function() {
		$("#informazioni_aggiuntive").hide();						   
	});
	<?php } ?>
	
	function mostraextra(){
		tmp = document.getElementById('level');
		myid = tmp.options[tmp.selectedIndex].value;
		if(myid==4){
			$("#informazioni_aggiuntive").show();
		}else{
			$("#informazioni_aggiuntive").hide();			
		}
	}
	//--></script>
</head>
  
	<body><div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->
		
		<?php require_once("sidebar_messages.php"); ?>
		
		<div id="insert-content"> <!-- Main Content Section with everything -->
			
            <?php require_once("top_bar.php"); ?>

			
			<div class="content-box"><!-- Start Content Box -->
				
				<div class="content-box-header">
					
					<h3>Modifica Negoziante</h3>
					
					<ul class="content-box-tabs">
						<li><a href="#tab1" class="default-tab">Modifica Negoziante</a></li>
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
								<label>Nome e Cognome</label>
								<span id="sprytextfield1">
								<input class="text-input medium-input" type="text" id="nome" name="nome" value="<?php echo $row_Recordset1["nome"]; ?>" />
								<span class="textfieldRequiredMsg input-notification error png_bg">È obbligatorio specificare un valore.</span></span>
                                <br /><small>Es: Mario Rossi</small>
							  </p>		

							  <p>
								<label>Email</label>
								<span id="sprytextfield2">
                                <input class="text-input medium-input" type="text" id="email" name="email" value="<?php echo $row_Recordset1["email"]; ?>" />
                                <span class="textfieldRequiredMsg">È obbligatorio specificare un valore.</span><span class="textfieldInvalidFormatMsg">Formato non valido.</span></span><br /><small>Es: mariorossi@tin.it</small>
							  </p>		

							  <p>
								<label>Password</label>
								<span id="sprypassword1">
                                <input class="text-input medium-input" type="password" id="pswd" name="pswd" value="<?php echo $row_Recordset1["pswd"]; ?>" />
<span class="passwordMinCharsMsg">Numero minimo di caratteri non raggiunto.</span><span class="passwordMaxCharsMsg">Numero massimo di caratteri superato.</span></span><br /><small>Minimo 6 caratteri</small>
							  </p>		

							  <p>
								<label>Conferma Password</label>
								<span id="spryconfirm1">
                                <input class="text-input medium-input" type="password" id="pswd_conf" name="pswd_conf" value="<?php echo $row_Recordset1["pswd"]; ?>" />
                                <span class="confirmInvalidMsg">I valori non corrispondono.</span><span class="confirmRequiredMsg">È obbligatorio specificare un valore.</span></span><br /><small>Minimo 6 caratteri</small>
							  </p>		

                                <p>
                                <label>Utente Attivo</label>
                                <input type="checkbox" name="is_attivo" value="1" <?php if (!(strcmp($row_Recordset1['is_attivo'],"1"))) {echo "checked=\"checked\"";} ?> /> Abilita l'utente ad accedere allo shop online riservato ai negozi.
                                </p>

								<input type="hidden" name="level" id="level" value="4" />

                                <div id="informazioni_aggiuntive">
                                <p>
								<label>Denominazione Negozio:</label>
                                <input type="text" id="negozio" name="negozio" class="text-input medium-input" value="<?php echo $row_Recordset1["negozio"]; ?>" />
							    </p>
                                <p>
								<label>Indirizzo:</label>
                                <textarea id="indirizzo" name="indirizzo" cols="40" rows="4"><?php echo $row_Recordset1["indirizzo"]; ?></textarea>                               
							    </p>
                                <p>
								<label>Telefono/i:</label>
                                <textarea id="telefono" name="telefono" cols="40" rows="2"><?php echo $row_Recordset1["telefono"]; ?></textarea>                               
							    </p>
                                <p>
                                <label>Listino:</label> 
                                Italia <input type="radio" name="listino" value="1" <?php if($row_Recordset1["listino"]=="1"){ echo ' checked="checked" '; } ?> /> | 
                                Estero <input type="radio" name="listino" value="2" <?php if($row_Recordset1["listino"]=="2"){ echo ' checked="checked" '; } ?> />
                                </p>
                                </div>		                            
                             	
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
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "email");
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1", {minChars:6, maxChars:20, isRequired:false});
var spryconfirm1 = new Spry.Widget.ValidationConfirm("spryconfirm1", "pswd");
//-->
    </script>
    </body>
    
</html>
<?php
mysqli_free_result($Recordset1);
?>
