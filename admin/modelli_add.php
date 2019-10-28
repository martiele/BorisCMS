<?php 
require_once('../Connections/std_conn.php');
require_once('../funzioni.php');

@include 'photo.php';	

$MM_authorizedUsers = "1,2,3";
require_once("restrict.php");

$sufx_sezione="modelli";
$tabella = "dny_modelli";

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


if(isset($_POST["nomeform"]) && $_POST["nomeform"]=="vv"){
	
	//Recupero il prossimo num ordinamento
	mysqli_select_db($std_conn, $database_std_conn);
	$query_nextOrdinamento = sprintf("SELECT MAX(ordinamento) massimo FROM %s WHERE eliminato=0",$tabella);
	$nextOrdinamento = mysqli_query($std_conn, $query_nextOrdinamento) or die(mysqli_error($std_conn));
	$row_nextOrdinamento = mysqli_fetch_assoc($nextOrdinamento);
	$totalRows_nextOrdinamento = mysqli_num_rows($nextOrdinamento);
	if($totalRows_nextOrdinamento>0){
		$ordinamento = $row_nextOrdinamento["massimo"] + 1;
	}else{
		$ordinamento = 1;
	}
	mysqli_free_result($nextOrdinamento);
	
	$data_attuale=date("Y-m-d H:i:s");	
	$insertSQL = sprintf("INSERT INTO %s (nome, descrizione_pagina, ordinamento, is_attivo, modified, created) VALUES (%s, %s, %s, %s, %s, %s)",
		   $tabella,
		   GetSQLValueString($_POST["nome"], "text"),
		   GetSQLValueString($_POST["descrizione_pagina"], "text"),
		   GetSQLValueString($ordinamento, "float"),
		   GetSQLValueString($_POST["attivo"], "int"),
		   GetSQLValueString($data_attuale, "date"),
		   GetSQLValueString($data_attuale, "date"));			   
	mysqli_select_db($std_conn, $database_std_conn);
	$Result1 = mysqli_query($std_conn, $insertSQL) or die(mysqli_error($std_conn));
	$id_inserito = mysqli_insert_id($std_conn);

	require("upload_modello.php");		

	logThis($sufx_sezione, "Aggiunto", $id_inserito);
	header("Location: ".$sufx_sezione."_gest.php");

}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"> 
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title><?php echo $_SESSION["www_title"]; ?> - Aggiungi Modello</title>
		<?php require_once("header.php"); ?>
        <script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
	    <link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />

        
</head>
  
	<body><div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->
		
		<?php require_once("sidebar_messages.php"); ?>
		
		<div id="insert-content"> <!-- Main Content Section with everything -->
			
            <?php require_once("top_bar.php"); ?>

        	<form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data">
       		<input type="hidden" name="nomeform" value="vv" />
	
                <p>
                    <label>Nome del modello</label>
                    <span id="sprytextfield1">
                    <input class="text-input small-input" type="text" id="nome" name="nome" value="" required />
                    <span class="textfieldRequiredMsg">È obbligatorio specificare un valore.</span></span><br /><small>Es: Modello Francia</small>
                </p>

                <p>
                    <label>Descrizione del modello</label>
                    <textarea class="text-input medium-input" id="descrizione_pagina" name="descrizione_pagina"></textarea>
					<br /><small>Es: Questo modello e' quello provato in data xxx</small>
                </p>
              

            	<p>
              		<label>File Excel del modello di output</label>
              	    <input type="file" name="fileimg" id="fileimg" />               
            	</p>

                <p>
                    <label>E' attivo?</label>
                    <input type="radio" id="attivoSi" name="attivo" value="1" checked="checked" />S&Igrave; &nbsp;&nbsp;&nbsp;
                    <input type="radio" id="attivoNo" name="attivo" value="0" />No
                    <br />
          		</p>
                
                <p>
                    <input class="button" type="submit" value="Inserisci" />
                </p>
            </form>			

			
            <?php require_once("footer.php"); ?>

			
		</div> <!-- End #insert-content -->
		
	</div>
    <script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
    </script>
	</body>
  
</html>