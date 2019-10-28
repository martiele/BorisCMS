<?php 
require_once('../Connections/std_conn.php');
require_once('../funzioni.php');

$MM_authorizedUsers = "1,2";
require_once("restrict.php");

$sufx_sezione="negozianti";
$tabella = "dny_utente";

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
		
	$is_attivo = ($_POST['is_attivo']=="1")?1:0;
	$insertSQL = sprintf("INSERT INTO %s (nome, email, pswd, level, is_attivo, negozio, telefono, indirizzo, listino, ordinamento) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
			   $tabella,
			   GetSQLValueString($_POST['nome'], "text"),
			   GetSQLValueString($_POST['email'], "text"),
			   GetSQLValueString($_POST['pswd'], "text"),
			   GetSQLValueString($_POST['level'], "int"),
			   GetSQLValueString($is_attivo, "int"),
			   GetSQLValueString($_POST['negozio'], "text"),
			   GetSQLValueString($_POST['telefono'], "text"),
			   GetSQLValueString($_POST['indirizzo'], "text"),
			   GetSQLValueString($_POST['listino'], "int"),
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
		<title><?php echo $_SESSION["www_title"]; ?> - Aggiungi Negoziante</title>
		<?php require_once("header.php"); ?>
    <script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
        <script src="../SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
        <script src="../SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
        <link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />


    <link href="../SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
    <link href="../SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript"><!--//
	/*
	$(document).ready(function() {
		$("#informazioni_aggiuntive").hide();						   
	});
	*/
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
					
					<h3>Aggiunta Negoziante</h3>
					
					<ul class="content-box-tabs">
						<li><a href="#tab1" class="default-tab">Aggiunta Negoziante</a></li>
					</ul>
					
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
					
					<div class="tab-content default-tab" id="tab1"> <!-- This is the target div. id must match the href of this div's tab -->
                    
						
					  <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data">
       	<input type="hidden" name="nomeform" value="vv" />
							
							<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
								
								
							  <p>
								<label>Nome e Cognome</label>
								<span id="sprytextfield1">
								<input class="text-input medium-input" type="text" id="nome" name="nome" />
								<span class="textfieldRequiredMsg input-notification error png_bg">È obbligatorio specificare un valore.</span></span>
                                <br /><small>Es: Mario Rossi</small>
							  </p>		

							  <p>
								<label>Email</label>
								<span id="sprytextfield2">
                                <input class="text-input medium-input" type="text" id="email" name="email" />
                                <span class="textfieldRequiredMsg">È obbligatorio specificare un valore.</span><span class="textfieldInvalidFormatMsg">Formato non valido.</span></span>
                                <br /><small>Es: mariorossi@tin.it</small>
							  </p>		

							  <p>
								<label>Password</label>
								<span id="sprytextfield1"><span id="sprypassword1">
                                <input class="text-input medium-input" type="password" id="pswd" name="pswd" />
                                <span class="passwordRequiredMsg">È obbligatorio specificare un valore.</span><span class="passwordMinCharsMsg">Numero minimo di caratteri non raggiunto.</span><span class="passwordMaxCharsMsg">Numero massimo di caratteri superato.</span></span><span class="textfieldRequiredMsg input-notification error png_bg">È obbligatorio specificare un valore.</span></span>
                                <br /><small>Minimo 6 caratteri</small>
							  </p>		

							  <p>
								<label>Conferma Password</label>
								<span id="sprytextfield1"><span id="spryconfirm1">
								<input class="text-input medium-input" type="password" id="pswd_conf" name="pswd_conf" />
								<span class="confirmRequiredMsg">È obbligatorio specificare un valore.</span><span class="confirmInvalidMsg">I valori non corrispondono.</span></span><span class="textfieldRequiredMsg input-notification error png_bg">È obbligatorio specificare un valore.</span></span>
                                <br /><small>Minimo 6 caratteri</small>
							  </p>		
                                <p>
                                <label>Utente Attivo</label>
                                <input type="checkbox" name="is_attivo" value="1" checked="checked" /> Abilita l'utente ad accedere allo shop online riservato ai negozi.
                                </p>
                                
                                <input type="hidden" name="level" id="level" value="4" />
		                         
                                <div id="informazioni_aggiuntive">
                                <p>
								<label>Denominazione Negozio:</label>
                                <input type="text" id="negozio" name="negozio" class="text-input medium-input" />
							    </p>
                                <p>
								<label>Indirizzo:</label>
                                <textarea id="indirizzo" name="indirizzo" cols="40" rows="4"></textarea>                               
							    </p>
                                <p>
								<label>Telefono/i:</label>
                                <textarea id="telefono" name="telefono" cols="40" rows="2"></textarea>                               
							    </p>
                                <p>
                                <label>Listino:</label> 
                                Italia <input type="radio" name="listino" value="1" checked="checked" /> | 
                                Estero <input type="radio" name="listino" value="2" />
                                </p>
                                </div>
                                	
							
								
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
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "email");
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1", {minChars:6, maxChars:20});
var spryconfirm1 = new Spry.Widget.ValidationConfirm("spryconfirm1", "pswd");
//-->
    </script>
	</body>
  
</html>
