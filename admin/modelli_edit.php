<?php 
require_once('../Connections/std_conn.php');
require_once('../funzioni.php');

@include 'photo.php';	

$MM_authorizedUsers = "1,2,3";
require_once("restrict.php");

$sufx_sezione="modelli";
$tabella = "dny_modelli";

$colname_Recordset1 = "-1";
if (isset($_GET['ids'])) {
  $colname_Recordset1 = $_GET['ids'];
}
mysqli_select_db($std_conn, $database_std_conn);
$query_Recordset1 = sprintf("SELECT * FROM %s WHERE ids = %s", 
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



if(isset($_POST["nomeform"]) && $_POST["nomeform"]=="vv"){

	if((isset($_POST["remove_img"]) && $_POST["remove_img"]==1) || $_POST["hidden_remove_img"]==1){
		$insertSQL = sprintf("UPDATE %s SET 
						 fileimg=%s
						 WHERE ids=%s",
			   $tabella,
			   GetSQLValueString( NULL, "text"),
			   GetSQLValueString($_POST['id'], "int"));

	mysqli_select_db($std_conn, $database_std_conn);
	$Result1 = mysqli_query($std_conn, $insertSQL) or die(mysqli_error($std_conn));
	}
	
    $data_attuale=date("Y-m-d H:i:s");
	$insertSQL = sprintf("UPDATE %s SET 
						 nome=%s, descrizione_pagina=%s, is_attivo=%s, modified=%s
						 WHERE ids=%s",
			   $tabella,
			   GetSQLValueString($_POST['nome'], "text"),
               GetSQLValueString($_POST["descrizione_pagina"], "text"),
			   GetSQLValueString($_POST["attivo"], "int"),
			   GetSQLValueString($data_attuale, "date"),
			   GetSQLValueString($_POST['id'], "int"));

	mysqli_select_db($std_conn, $database_std_conn);
	$Result1 = mysqli_query($std_conn, $insertSQL) or die(mysqli_error($std_conn));

	$id_inserito = $_POST['id'];
	require("upload_modello.php");

	logThis($sufx_sezione, "Modificato", $id_inserito);
	header("Location: ".$sufx_sezione."_gest.php");

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"> 
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title><?php echo $_SESSION["www_title"]; ?> - Modifica Modello</title>
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
                <input type="hidden" name="id" value="<?php echo $_GET["ids"]; ?>" />    

            <p>
                <label>Nome del modello</label>
                <span id="sprytextfield1">
                <input class="text-input small-input" type="text" id="nome" name="nome" value="<?=$row_Recordset1["nome"]?>" required />
                <span class="textfieldRequiredMsg">È obbligatorio specificare un valore.</span></span><br /><small>Es: Modello Francia</small>
            </p>

            <p>
                <label>Descrizione del modello</label>
                <textarea class="text-input medium-input" id="descrizione_pagina" name="descrizione_pagina"><?=$row_Recordset1["descrizione_pagina"]?></textarea>
                <br /><small>Es: Questo modello &egrave; quello provato in data xxx</small>
            </p>

            <p>
               <label>File Excel del modello di output</label>
               <?php if(strcmp($row_Recordset1["fileimg"],NULL)!=0){?>
	               	<a title="scarica modello" href="<?=$_SESSION["path_upload_admin"]?><?=$_SESSION["path_filemodelli"]?><?=$row_Recordset1["fileimg"]?>"><?=$row_Recordset1["fileimg"]?></a>
               <?php }else{ ?>
					<label> Nessun file selezionato </label>
			   <?php }?>
               <br /> <span> Modifica file corrente:</span><br />
               <input type="file" name="fileimg" id="fileimg"  />    
               <br /><small id="smallText">Inserire file Excel</small>               
               <br /><br /><span>Rimuovi file corrente: <input type="checkbox" id="removeImg" name="remove_img" value="1"/></span>
	        </p>

            

          	<p>
                <label>E' attivo?</label>
                <input type="radio" id="attivoSi" name="attivo" value="1"  <?php if($row_Recordset1["is_attivo"]==1) echo 'checked="checked"' ?>/>S&Igrave; &nbsp;&nbsp;&nbsp;
                <input type="radio" id="attivoNo" name="attivo" value="0"  <?php if($row_Recordset1["is_attivo"]==0) echo 'checked="checked"' ?>/>No
                <br />
          	</p>
            	
            <p>
                <input class="button" type="submit" value="Aggiorna" />&nbsp;&nbsp;<input class="button" type="button" value="Chiudi" onclick="javascript:location.href='<?php echo $sufx_sezione; ?>_gest.php';" />
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
<?php
mysqli_free_result($Recordset1);
?>
