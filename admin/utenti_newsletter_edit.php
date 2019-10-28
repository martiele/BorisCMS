<?php 
require_once('../Connections/std_conn.php');
require_once('../funzioni.php');

$MM_authorizedUsers = "1,2";
require_once("restrict.php");

$sufx_sezione="utenti_newsletter";
$tabella = "dny_utente_newsletter";

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
	$accesso_completo = ($_POST['accesso_completo']=="1")?1:0;
	$mostra_dati_sensibili = ($_POST['mostra_dati_sensibili']=="1")?1:0;	
	$ricevi_notifiche_wp = ($_POST['ricevi_notifiche_wp']=="1")?1:0;	
	$default_unselected = ($_POST['default_unselected']=="1")?1:0;	
	
	$insertSQL = sprintf("UPDATE %s SET 
						 nome=%s,
						 azienda=%s,
						 email=%s, 
						 %s
						 is_attivo=%s,
						 accesso_completo=%s,
						 mostra_dati_sensibili=%s,
						 ricevi_notifiche_wp=%s,
						 default_unselected=%s
						 WHERE id=%s",
			   $tabella,
			   GetSQLValueString($_POST['nome'], "text"),
			   GetSQLValueString($_POST['azienda'], "text"),
			   GetSQLValueString($_POST['email'], "text"),
			   $qry_add,
			   GetSQLValueString($is_attivo, "int"),
			   GetSQLValueString($accesso_completo, "int"),
			   GetSQLValueString($mostra_dati_sensibili, "int"),
			   GetSQLValueString($ricevi_notifiche_wp, "int"),
			   GetSQLValueString($default_unselected, "int"),
			   GetSQLValueString($_POST['id'], "int"));

	mysqli_select_db($std_conn, $database_std_conn);
	$Result1 = mysqli_query($std_conn, $insertSQL) or die(mysqli_error($std_conn));
	$id_inserito = $_POST['id'];
	
	//categorie
	$categorieSQL = sprintf("DELETE FROM dny_utente_newsletter_categoria WHERE id_utente_newsletter=%s",
		GetSQLValueString($id_inserito, "int"));
	mysqli_select_db($std_conn, $database_std_conn);
	$Result1 = mysqli_query($std_conn, $categorieSQL) or die(mysqli_error($std_conn));	
	
	mysqli_select_db($std_conn, $database_std_conn);
	$query_RecordsetCat = sprintf("SELECT A.*, B.nome FROM (dny_categoria as A LEFT JOIN dny_categoria_lingua as B ON A.id = B.id_ref) WHERE A.deleted=0 AND A.id_linea=1 AND (B.id_lingua=%s OR B.id_lingua is Null) ORDER BY A.ordinamento ASC", 
						GetSQLValueString($_SESSION["linguadefault"],"int"));
	$RecordsetCat = mysqli_query($std_conn, $query_RecordsetCat) or die(mysqli_error($std_conn));
	$row_RecordsetCat = mysqli_fetch_assoc($RecordsetCat);
	$totalRows_RecordsetCat = mysqli_num_rows($RecordsetCat);
	if($totalRows_RecordsetCat>0){
		do{
			$idcat = $row_RecordsetCat["id"];
			if(isset($_POST["cat_".$idcat]) && ($_POST["cat_".$idcat]=="1")){
				$categorieSQL = sprintf("INSERT INTO dny_utente_newsletter_categoria (id_utente_newsletter, id_categoria) VALUES (%s, %s)",
					GetSQLValueString($id_inserito, "int"),
					GetSQLValueString($idcat, "int"));
				mysqli_select_db($std_conn, $database_std_conn);
				$Result1 = mysqli_query($std_conn, $categorieSQL) or die(mysqli_error($std_conn));
			}
    	}while($row_RecordsetCat = mysqli_fetch_assoc($RecordsetCat)); 
    }
    mysqli_free_result($RecordsetCat);

logThis($sufx_sezione, "Modificato", $id_inserito);
header("Location: ".$sufx_sezione."_gest.php");

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"> 
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title><?php echo $_SESSION["www_title"]; ?> - Modifica Utente Area Riservata</title>
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
					
					<h3>Modifica Utente Area Riservata</h3>
					
					<ul class="content-box-tabs">
						<li><a href="#tab1" class="default-tab">Modifica Utente</a></li>
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
								<label>Azienda</label>
								<input class="text-input medium-input" type="text" id="azienda" name="azienda" value="<?php echo $row_Recordset1["azienda"]; ?>" />
                                <br /><small>Es: Company & Co.</small>
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


<?php
mysqli_select_db($std_conn, $database_std_conn);
$query_RecordsetCat = sprintf("SELECT A.*, B.nome FROM (dny_categoria as A LEFT JOIN dny_categoria_lingua as B ON A.id = B.id_ref) WHERE A.deleted=0 AND A.id_linea=1 AND (B.id_lingua=%s OR B.id_lingua is Null) ORDER BY A.ordinamento ASC", 
					GetSQLValueString($_SESSION["linguadefault"],"int"));
$RecordsetCat = mysqli_query($std_conn, $query_RecordsetCat) or die(mysqli_error($std_conn));
$row_RecordsetCat = mysqli_fetch_assoc($RecordsetCat);
$totalRows_RecordsetCat = mysqli_num_rows($RecordsetCat);
if($totalRows_RecordsetCat>0){
?>
    <p>
    <label>Categorie di interesse:</label>
    <?php do{ 
mysqli_select_db($std_conn, $database_std_conn);
$query_RecordsetSelected = sprintf("SELECT * FROM dny_utente_newsletter_categoria WHERE id_utente_newsletter=%s AND id_categoria=%s", 
					GetSQLValueString($row_Recordset1['id'],"int"),
					GetSQLValueString($row_RecordsetCat['id'],"int"));
$RecordsetSelected = mysqli_query($std_conn, $query_RecordsetSelected) or die(mysqli_error($std_conn));
if($row_RecordsetSelected = mysqli_fetch_assoc($RecordsetSelected)){
	$dochecked = true;
}else{
	$dochecked = false;	
}
mysqli_free_result($RecordsetSelected);	
	?>
    	<input type="checkbox" name="cat_<?=$row_RecordsetCat["id"]?>" value="1" <?php if($dochecked){ echo 'checked="checked"'; } ?> /> <?=$row_RecordsetCat["nome"]?><br />
    <?php }while($row_RecordsetCat = mysqli_fetch_assoc($RecordsetCat)); ?>
    <small>L'utente verr&agrave; avvisato della pubblicazione di articoli nelle categorie selezionate.</small>
    </p>
<?php
}
mysqli_free_result($RecordsetCat);
?>

                              <p>
                                <label>Accesso completo</label>                
                                <input type="checkbox" name="accesso_completo" value="1" <?php if (!(strcmp($row_Recordset1['accesso_completo'],"1"))) {echo "checked=\"checked\"";} ?> /> Se deselezionato, l'utente potrà accedere solo all'elenco delle gare in corso MA NON alle altre sezioni dell'area riservata.
                              </p>
                              <p>
                                <label>Mostra SOLO dati sensibili</label>                
                                <input type="checkbox" name="mostra_dati_sensibili" value="1" <?php if (!(strcmp($row_Recordset1['mostra_dati_sensibili'],"1"))) {echo "checked=\"checked\"";} ?> /> Se selezionato, l'utente potrà accedere solamente ai documenti elencati nella categoria "Documentazione Consorzio".
                              </p>
								  
                              <p>
                                <label>Abilita utente</label>                
                                <input type="checkbox" name="is_attivo" value="1" <?php if (!(strcmp($row_Recordset1['is_attivo'],"1"))) {echo "checked=\"checked\"";} ?> /> abilita l'utente ad accedere all'area riservata.
                              </p>

                            <p>
                                <label>Notifiche Wordpress</label>
                                <input type="checkbox" name="ricevi_notifiche_wp" value="1" <?php if (!(strcmp($row_Recordset1['ricevi_notifiche_wp'],"1"))) {echo "checked=\"checked\"";} ?> /> Ricevi Email di Notifica per i nuovi articoli scritti dal pannello di Wordpress.
                            </p>	

                            <p>
                                <label>Disabilita utente da richieste documentazione</label>
                                <input type="checkbox" name="default_unselected" value="1" <?php if (!(strcmp($row_Recordset1['default_unselected'],"1"))) {echo "checked=\"checked\"";} ?> /> Checcka il box se vuoi che questo utente venga escluso (di default) dalle richieste documentazione - potrai comunque forzare l'inclusione dell'utente quando crei la richiesta 
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
