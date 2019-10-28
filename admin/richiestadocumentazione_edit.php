<?php 
require_once('../Connections/std_conn.php');
require_once('../funzioni.php');

$MM_authorizedUsers = "1,2,3";
require_once("restrict.php");

$sufx_sezione="richiestadocumentazione";
$tabella = "dny_richiestadocumentazione";

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


mysqli_select_db($std_conn, $database_std_conn);
$query_rs_lingua = "SELECT * FROM dny_lingua WHERE deleted = 0 ORDER BY ordinamento ASC";
$rs_lingua = mysqli_query($std_conn, $query_rs_lingua) or die(mysqli_error($std_conn));
$row_rs_lingua = mysqli_fetch_assoc($rs_lingua);
$totalRows_rs_lingua = mysqli_num_rows($rs_lingua);
if($totalRows_rs_lingua<=0){
	die();
}


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if($_POST["nomeform"]=="vv"){
	$is_attivo = ($_POST['is_attivo']=="1")?1:0;
	$segnalato_newsletter = ($_POST['segnalato_newsletter']=="1")?1:0;
	$insertSQL = sprintf("UPDATE %s SET 
						 data_inserimento=%s,
						 link=%s, 
						 is_attivo=%s, 
						 progressivo=%s,
						 segnalato_newsletter=%s,
						 modified=%s
						 WHERE id=%s",
			   $tabella,
			   GetSQLValueString(data_a_database($_POST['data_inserimento'],"-"), "date"),
			   GetSQLValueString($_POST['link'], "text"),
			   GetSQLValueString($is_attivo, "int"),
			   GetSQLValueString($_POST['progressivo'], "text"),
			   GetSQLValueString($segnalato_newsletter, "int"),
			   GetSQLValueString(data_a_database(date("d/m/Y")),"date"),
			   GetSQLValueString($_POST['id'], "int"));


	mysqli_select_db($std_conn, $database_std_conn);
	$Result1 = mysqli_query($std_conn, $insertSQL) or die(mysqli_error($std_conn));
	$id_inserito = $_POST['id'];


	//Rimuovo le associazioni utente
	$insertSQL = sprintf("DELETE FROM `dny_richiestadocumentazione_utenti_invitati` WHERE id_richiestadocumentazione=%s",
			   GetSQLValueString($id_inserito, "int"));
	mysqli_select_db($std_conn, $database_std_conn);
	$Result1 = mysqli_query($std_conn, $insertSQL) or die(mysqli_error($std_conn));

	//Aggiungo le nuove associazioni
	if(!empty($_POST['utenti_invitati'])) {
	    foreach($_POST['utenti_invitati'] as $id_utentenotifica) {
			$insertSQL = sprintf("INSERT INTO `dny_richiestadocumentazione_utenti_invitati` (`id_richiestadocumentazione`, `id_utente_newsletter`) VALUES (%s, %s);",
					   GetSQLValueString($id_inserito, "int"),
					   GetSQLValueString($id_utentenotifica, "int"));
			mysqli_select_db($std_conn, $database_std_conn);
			$Result1 = mysqli_query($std_conn, $insertSQL) or die(mysqli_error($std_conn));
	    }
	}


	//Rimuovo le associazioni (lingua) precedenti e le reinserisco
	$insertSQL = sprintf("DELETE FROM %s WHERE id_ref=%s",
	   $tabella."_lingua",
	   GetSQLValueString($id_inserito, "int"));
	mysqli_select_db($std_conn, $database_std_conn);
	$Result1 = mysqli_query($std_conn, $insertSQL) or die(mysqli_error($std_conn));	
	do{ 
		$i = $row_rs_lingua["id"];
		$nome = $_POST["nome".$i];
		$descrizione = $_POST["descrizione".$i];
		$is_attivo = ($_POST['is_attivo'.$i]=="1")?1:0;
		if($nome!=""){
			$insertSQL = sprintf("INSERT INTO %s (id_ref, id_lingua, nome, descrizione, is_attivo) VALUES (%s, %s, %s, %s, %s)",
			   $tabella."_lingua",
			   GetSQLValueString($id_inserito, "int"),
			   GetSQLValueString($i, "int"),
			   GetSQLValueString($nome, "text"),
			   GetSQLValueString($descrizione, "text"),
			   GetSQLValueString($is_attivo, "int"));
			mysqli_select_db($std_conn, $database_std_conn);
			$Result1 = mysqli_query($std_conn, $insertSQL) or die(mysqli_error($std_conn));
		}	
	}while($row_rs_lingua = mysqli_fetch_assoc($rs_lingua));
	mysqli_data_seek($rs_lingua,0);
	$row_rs_lingua = mysqli_fetch_assoc($rs_lingua);	

	$folder = "uploads";
	$progr = 1;
	foreach ($_POST["ax-uploaded-files"] as $key => $value) {
		
		$nomefile=$value;
		// In PHP versions earlier than 4.1.0, $HTTP_POST_FILES should be used instead
		// of $_FILES.
		if ($nomefile!="" && $id_inserito>0){
			$pos = strrpos($nomefile, "/");
			$rest = substr($nomefile, $pos+1); // restituisceil nome del file
			$nomefiledef = $id_inserito."_".$rest;
			$nomefiledef = clean($nomefiledef);
			rename(dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."temp".DIRECTORY_SEPARATOR.$rest,
			 dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."richiestedocumentazione".DIRECTORY_SEPARATOR.$nomefiledef);
			
			$insertSQL = sprintf("INSERT INTO `dny_allegati` (`nomefile`, `tabella`, `id_ref`, `ordinamento`, `data_aggiunta`) VALUES (%s, %s, %s, %s, %s);",
				GetSQLValueString($nomefiledef, "text"),
				GetSQLValueString($sufx_sezione, "text"),
				GetSQLValueString($id_inserito, "int"),
				GetSQLValueString($progr, "int"),
				GetSQLValueString(data_a_database(date("d/m/Y")),"date"));
			mysqli_select_db($std_conn, $database_std_conn);
			$Result1 = mysqli_query($std_conn, $insertSQL) or die(mysqli_error($std_conn));
		}
	}
	
	


logThis($sufx_sezione, "Modificato", $id_inserito);
header("Location: ".$sufx_sezione."_gest.php");

}else{
	
	if($_GET["deleteallegato"]==1){
		$id_ref = $_GET["idallegato"];
		if($id_ref>0){
			mysqli_select_db($std_conn, $database_std_conn);
			$query_RS2 = sprintf("SELECT * FROM dny_allegati WHERE id=%s", 
								GetSQLValueString($id_ref, "int"));
			$RS2 = mysqli_query($std_conn, $query_RS2) or die(mysqli_error($std_conn));
			$row_RS2 = mysqli_fetch_assoc($RS2);
			$totalRows_row_RS2 = mysqli_num_rows($RS2);
			if($totalRows_row_RS2>0){ 
				  $upload_directory = "../public/richiestedocumentazione";
				  $nomefile = $upload_directory . "/" . $row_RS2["nomefile"];
				  if(($row_RS2["nomefile"]!="")&&(file_exists($nomefile))){
					unlink($nomefile);
				  }	
			}		
			mysqli_free_result($RS2);
			
			$query_RS2 = sprintf("DELETE FROM dny_allegati WHERE id=%s", 
								GetSQLValueString($id_ref, "int"));
			$RS2 = mysqli_query($std_conn, $query_RS2) or die(mysqli_error($std_conn));			
		}
	}		
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"> 
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo $_SESSION["www_title"]; ?> - Modifica Articolo</title>
		<?php require_once("header.php"); ?>

		<script language="JavaScript" src="js/calendar_eu.js"></script>
        <link rel="stylesheet" href="css/calendar.css">

        <script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
	    <link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />

<!-- TinyMCE -->
	<script type="text/javascript" src="../tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
    <script type="text/javascript">
tinyMCE.init({
	// General options
	elements: "elm1",
	mode : "textareas",
	theme : "advanced",
	plugins : "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,imagemanager",

	// Theme options
	theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
	theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
	theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
	theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_resizing : true,

	// Drop lists for link/image/media/template dialogs
	template_external_list_url : "js/template_list.js",
	external_link_list_url : "js/link_list.js",
	external_image_list_url : "js/image_list.js",
	media_external_list_url : "js/media_list.js",

});
    </script>
	<!-- /TinyMCE -->

	<script src="js/jquery.js" type="text/javascript"></script>   
    <script src="js/ajaxupload-min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="css/baseTheme/style.css" type="text/css" media="all" />

</head>
  
	<body><div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->
		
		<?php require_once("sidebar_messages.php"); ?>
		
		<div id="insert-content"> <!-- Main Content Section with everything -->
			
            <?php require_once("top_bar.php"); ?>

	        <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1">
                <input type="hidden" name="nomeform" value="vv" />
                <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>" />
                
			<div class="content-box"><!-- Start Content Box -->
				
				<div class="content-box-header">
					
					<h3>Modifica Articolo</h3>
					
					<ul class="content-box-tabs">
						<?php
						do{ 
							$i = $row_rs_lingua["id"];
						?>
						<li><a href="#tab<?=$i?>" <?php echo ($i == $_SESSION["linguadefault"])?'class="default-tab"':''; ?>><?=$row_rs_lingua["nome"]?></a></li>
                        <?php
						}while($row_rs_lingua = mysqli_fetch_assoc($rs_lingua));
						mysqli_data_seek($rs_lingua,0);
						$row_rs_lingua = mysqli_fetch_assoc($rs_lingua);
						?>
               		</ul>
					
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
					
					<?php
					do{ 
						$i = $row_rs_lingua["id"];

						mysqli_select_db($std_conn, $database_std_conn);
						$query_rs_info = sprintf("SELECT * FROM %s WHERE id_ref=%s AND id_lingua=%s",
							$tabella."_lingua",
							GetSQLValueString($colname_Recordset1, "int"),
							GetSQLValueString($i, "int"));
						$rs_info = mysqli_query($std_conn, $query_rs_info) or die(mysqli_error($std_conn));
						$row_rs_info = mysqli_fetch_assoc($rs_info);

					?>
					<div class="tab-content <?php echo ($i == $_SESSION["linguadefault"])?'default-tab':''; ?>" id="tab<?=$i?>"> <!-- This is the target div. id must match the href of this div's tab -->
                    
						
							<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
								
							<p>
								<label>Titolo (<?=$row_rs_lingua["nome"]?>)</label>
								<input class="text-input medium-input" type="text" id="nome<?=$i?>" name="nome<?=$i?>" value="<?=$row_rs_info["nome"]?>" />
                                <br /><small>Es: Titolo della Notizia</small>
							  </p>		

							  <p>
								<label>Messaggio (<?=$row_rs_lingua["nome"]?>)</label>
								<textarea id="descrizione<?=$i?>" name="descrizione<?=$i?>" rows="5" cols="40"><?=$row_rs_info["descrizione"]?></textarea>
                                <br /><small>Testo completo della news</small>
							  </p>		
                            
	                            <input type="hidden" name="is_attivo<?=$i?>" value="1" />  
    
							</fieldset>
							
							<div class="clear"></div><!-- End .clear -->
												
					</div> <!-- End #tabX -->
					<?php
						mysqli_free_result($rs_info);
					}while($row_rs_lingua = mysqli_fetch_assoc($rs_lingua));
					mysqli_data_seek($rs_lingua,0);
					$row_rs_lingua = mysqli_fetch_assoc($rs_lingua);
					?>		
					
        
					
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->



			<div class="content-box"><!-- Start Content Box -->				
				<div class="content-box-header">					
					<h3>Seleziona Utenti</h3>
						<ul class="content-box-tabs">
							<li><a href="#tab1a" class="default-tab">Seleziona</a></li>
						</ul>					
					<div class="clear"></div>
				</div> <!-- End .content-box-header -->
				<div class="content-box-content">
					<div class="tab-content default-tab" id="tab1a">
							<fieldset>
							  <div class="scelta_utenti">
<?php
mysqli_select_db($std_conn, $database_std_conn);
$query_utok = sprintf("SELECT id, nome, email, azienda, default_unselected FROM dny_utente_newsletter WHERE deleted=0 AND is_attivo=1 AND accesso_completo=1 AND mostra_dati_sensibili=0 ORDER BY nome ASC, email ASC");
$rs_utok = mysqli_query($std_conn, $query_utok) or die(mysqli_error($std_conn));
$row_utok = mysqli_fetch_assoc($rs_utok);
$totalRows_utok = mysqli_num_rows($rs_utok);
if($totalRows_utok>0){
	do{
		mysqli_select_db($std_conn, $database_std_conn);
		$query_ce = sprintf("SELECT * FROM dny_richiestadocumentazione_utenti_invitati WHERE  id_richiestadocumentazione=%s AND id_utente_newsletter=%s",
			$_GET["id"],
			$row_utok["id"]
		);
		$rs_ce = mysqli_query($std_conn, $query_ce) or die(mysqli_error($std_conn));
		$totalRows_ce = mysqli_num_rows($rs_ce);
		$selezionato = ($totalRows_ce>0) ? 'checked="checked"' : '';

		mysqli_free_result($rs_ce);

		echo '<div class="box_utente">
				<input '.$selezionato.' type="checkbox" name="utenti_invitati[]" data-default="'.$row_utok["default_unselected"].'" class="opzione" value="'.$row_utok["id"].'">
				'.$row_utok["nome"].' ('.$row_utok["email"].')
			  </div>';
	}while($row_utok = mysqli_fetch_assoc($rs_utok));
}
mysqli_free_result($rs_utok);
?>
								<div class="clear_invisibile">&nbsp;</div>
								<div class="controlli">
									<a id="selall" href="#">Seleziona tutti</a> | 
									<a id="selnone" href="#">Deseleziona tutti</a> | 
									<a id="seldef" href="#">Selezione default</a>
								</div>
							  </div>	
						  	</fieldset>	
							<div class="clear"></div><!-- End .clear -->
					</div> <!-- End #tabX -->
				</div> <!-- End .content-box-content -->
			</div> <!-- End .content-box -->	

			<script type="text/javascript">
			function checkallbutdefault(){
			    jQuery('input.opzione').prop('checked', true);
			    jQuery('input.opzione:not([data-default="0"])').prop('checked', false);
			}
			jQuery("#selall").click(function(e){
				e.preventDefault();
			    jQuery('input.opzione').prop('checked', true);
			});
			jQuery("#selnone").click(function(e){
				e.preventDefault();
			    jQuery('input.opzione').prop('checked', false);
			});
			jQuery("#seldef").click(function(e){
				e.preventDefault();
			    checkallbutdefault();
			});	
			// jQuery( document ).ready(function() {
			//     checkallbutdefault();
			// });
			</script>	
   

                <p>
                  <label>Numerico progressivo (opzionale)</label>
                  <input class="text-input medium-input" type="text" id="progressivo" name="progressivo" value="<?=$row_Recordset1['progressivo']?>" />
				  <br /><small>Numero Identificativo articolo secondo la numerazione interna [questo dato rimarrà nascosto]</small>
                </p>

				<p>
                    <label>Data di Inserimento (opzionale)</label>
                    
                    <input class="text-input small-input" type="text" id="data_inserimento" name="data_inserimento" readonly="readonly" value="<?php echo str_replace("/","-",data_a_video($row_Recordset1['data_inserimento'])); ?>" />
<script language="JavaScript" type="text/javascript">	
new tcal ({
'formname': 'form1',
'controlname': 'data_inserimento'
});	</script> <small><a href="#cancella" onclick="javascript:document.getElementById('data_inserimento').value='';">cancella</a></small>
                    <br /><small>Utilizzare il calendario a lato della casella [questo campo non verrà pubblicato, serve solo per uso interno]</small>
                </p>



				<p>
                	<label>Allega Media e Documenti (opzionale)</label>
                	<div id="uploader_div" style="text-align:left"></div>
                </p>
                
<?php
mysqli_select_db($std_conn, $database_std_conn);
$query_RS2 = sprintf("SELECT * FROM dny_allegati WHERE id_ref=%s AND tabella=%s AND eliminato=0 ORDER BY ordinamento ASC", 
					GetSQLValueString($row_Recordset1['id'], "int"),
					GetSQLValueString($sufx_sezione, "text"));
$RS2 = mysqli_query($std_conn, $query_RS2) or die(mysqli_error($std_conn));
$row_RS2 = mysqli_fetch_assoc($RS2);
$totalRows_row_RS2 = mysqli_num_rows($RS2);
if($totalRows_row_RS2>0){ ?>
                <p>
                	<label>Media e Documenti già caricati</label>
                    <ul>
                	<?php do{ 
							if($row_RS2["nomefile"]!=""){
					?>
						<li><a href="../public/richiestedocumentazione/<?php echo $row_RS2["nomefile"]; ?>" target="_blank"><?php echo substr($row_RS2["nomefile"],strpos($row_RS2["nomefile"],"_")+1); ?></a> &nbsp;&nbsp; <a href="<?php echo $sufx_sezione; ?>_edit.php?id=<?php echo $row_Recordset1['id']; ?>&deleteallegato=1&idallegato=<?php echo $row_RS2["id"]; ?>"><img src="resources/images/icons/cross.png" alt="RImuovi File" title="Rimuovi File" /></a></li>
					
                    <?php	}
						 }while($row_RS2 = mysqli_fetch_assoc($RS2)); ?>
                    </ul>
                </p>
<?php 
} 
mysqli_free_result($RS2);
?>

				<p>
                  <label>Link Generico (opzionale)</label>
                  <span id="sprytextfield1">
                  <input class="text-input medium-input" type="text" id="link" name="link" value="<?=$row_Recordset1['link']?>" />
<span class="textfieldInvalidFormatMsg">Formato non valido.</span></span><br /><small>Es: http://www.bandobando.it/pagina.html</small>
                </p>


                <p>
                	<label>Gi&agrave; comunicato via mail</label>
                	<select name="segnalato_newsletter" id="segnalato_newsletter">
                    	<option value="1" <?php if (!(strcmp($row_Recordset1['segnalato_newsletter'],"1"))) {echo "selected=\"selected\"";} ?> >S&igrave;</option>
                        <option value="0" <?php if (!(strcmp($row_Recordset1['segnalato_newsletter'],"0"))) {echo "selected=\"selected\"";} ?> >No</option>
                    </select>
                    <small>Puoi modificare lo stato di invio se vuoi inviare nuovamente la comunicazione via mail.</small>
                </p>

 	
                
                <p>
                	<label>Mostra online</label>                
                	<input type="checkbox" name="is_attivo" value="1" <?php if (!(strcmp($row_Recordset1['is_attivo'],"1"))) {echo "checked=\"checked\"";} ?> /> mostra o nasconde questo articolo nell'area riservata.
              	</p>	

            
            <p>
                <input class="button" type="submit" value="Aggiorna" />&nbsp;&nbsp;<input class="button" type="button" value="Chiudi" onclick="javascript:location.href='<?php echo $sufx_sezione; ?>_gest.php';" />
            </p>
            </form>			

	
			
            <?php require_once("footer.php"); ?>

			
		</div> <!-- End #insert-content -->
		
	</div>
    <script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "url", {isRequired:false});
    </script>
    
    <script type="text/javascript">
	  $('#uploader_div').ajaxupload({
		url:'upload.php',
		remotePath:'../public/temp/',
		form:'parent',
		maxFiles: 20
	  }); 
	</script>
    
    </body>
    
</html>
<?php
mysqli_free_result($Recordset1);
mysqli_free_result($rs_lingua);
?>
