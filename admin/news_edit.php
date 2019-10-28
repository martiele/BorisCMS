<?php 
require_once('../Connections/std_conn.php');
require_once('../funzioni.php');

$MM_authorizedUsers = "1,2,3";
require_once("restrict.php");

$sufx_sezione="news";
$tabella = "dny_news";

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
	$archived = ($_POST['archived']=="1")?1:0;
	$richiedi_interesse = ($_POST['richiedi_interesse']=="1")?1:0;
	$segnalato_newsletter = ($_POST['segnalato_newsletter']=="1")?1:0;
	$is_ristretta_negoziata = ($_POST['is_ristretta_negoziata']=="1")?1:0;
	$insertSQL = sprintf("UPDATE %s SET 
						 id_sottocategoria=%s, 
						 data_scadenza=%s,
						 data_inserimento=%s,
						 ente=%s, 
						 link=%s, 
						 importo=%s, 
						 is_attivo=%s, 
						 archived=%s,
						 progressivo=%s,
						 richiedi_interesse=%s, 
						 segnalato_newsletter=%s,
						 is_ristretta_negoziata=%s,
						 modified=%s
						 WHERE id=%s",
			   $tabella,
			   GetSQLValueString($_POST['id_sottocategoria'], "int"),
			   GetSQLValueString(data_a_database($_POST['data_scadenza'],"-"), "date"),
			   GetSQLValueString(data_a_database($_POST['data_inserimento'],"-"), "date"),
			   GetSQLValueString($_POST['ente'], "text"),
			   GetSQLValueString($_POST['link'], "text"),
			   GetSQLValueString($_POST['importo'], "text"),
			   GetSQLValueString($is_attivo, "int"),
			   GetSQLValueString($archived, "int"),
			   GetSQLValueString($_POST['progressivo'], "text"),
			   GetSQLValueString($richiedi_interesse, "int"),
			   GetSQLValueString($segnalato_newsletter, "int"),
			   $is_ristretta_negoziata,
			   GetSQLValueString(data_a_database(date("d/m/Y")),"date"),
			   GetSQLValueString($_POST['id'], "int"));

	mysqli_select_db($std_conn, $database_std_conn);
	$Result1 = mysqli_query($std_conn, $insertSQL) or die(mysqli_error($std_conn));
	$id_inserito = $_POST['id'];

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

	$stringa_errori_file = "";
	$uploaddir = $_SESSION["path_upload_admin"];
	$directory = $uploaddir.'newseventi/';
	$nomeCampoForm = "img";
	$larg = 500;
	$alte = 700;
	
	//Rimuovi immagine
	if((isset($_POST["rimuovi_".$nomeCampoForm]))&&($_POST["rimuovi_".$nomeCampoForm]==1)){
		mysqli_select_db($std_conn, $database_std_conn);
		$query_rs_aggiorna_ordine = sprintf("SELECT * FROM %s WHERE id=%s",
											$tabella,
											GetSQLValueString($id_inserito, "int"));
		$rs_aggiorna_ordine = mysqli_query($std_conn, $query_rs_aggiorna_ordine) or die(mysqli_error($std_conn));
		$row_rs_aggiorna_ordine = mysqli_fetch_assoc($rs_aggiorna_ordine);
		$totalRows_rs_aggiorna_ordine = mysqli_num_rows($rs_aggiorna_ordine);
		if($totalRows_rs_aggiorna_ordine>0){
			$nomefile = $row_rs_aggiorna_ordine["img"];
			$ilfile = $directory.$nomefile;
			if(($nomefile!="")&&(file_exists($ilfile))){
				unlink($ilfile);
			}
		}
		mysqli_free_result($rs_aggiorna_ordine);	
	}
	


logThis($sufx_sezione, "Modificato", $id_inserito);
header("Location: ".$sufx_sezione."_gest.php");

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
								<span id="sprytextfield2">
								<label>Titolo (<?=$row_rs_lingua["nome"]?>)</label>
								<input class="text-input medium-input" type="text" id="nome<?=$i?>" name="nome<?=$i?>" value="<?=$row_rs_info["nome"]?>" />
								<span class="textfieldRequiredMsg input-notification error png_bg">È obbligatorio specificare un valore.</span></span>
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
    <select name="id_sottocategoria" id="id_sottocategoria" class="text-input medium-input">
    <?php 
	do{ 
	?>
   		<option value="cat_<?=$row_RecordsetCat["id"]?>" disabled="disabled" style="background-color:#CCC; color:#000;"><?=$row_RecordsetCat["nome"]?></option>
    <?php
		mysqli_select_db($std_conn, $database_std_conn);
		$query_RecordsetSubCat = sprintf("SELECT A.*, B.nome FROM (dny_sottocategoria as A LEFT JOIN dny_sottocategoria_lingua as B ON A.id = B.id_ref) WHERE A.deleted=0 AND A.id_categoria=%s AND (B.id_lingua=%s OR B.id_lingua is Null) ORDER BY A.ordinamento ASC", 
			GetSQLValueString($row_RecordsetCat["id"],"int"),
			GetSQLValueString($_SESSION["linguadefault"],"int"));
		$RecordsetSubCat = mysqli_query($std_conn, $query_RecordsetSubCat) or die(mysqli_error($std_conn));
		$row_RecordsetSubCat = mysqli_fetch_assoc($RecordsetSubCat);
		$totalRows_RecordsetSubCat = mysqli_num_rows($RecordsetSubCat);
		if($totalRows_RecordsetSubCat>0){	
			do{
	?>
    		<option value="<?=$row_RecordsetSubCat["id"]?>" <?php if(!strcmp($row_Recordset1["id_sottocategoria"],$row_RecordsetSubCat["id"])){ echo 'selected="selected"'; } ?> ><?=$row_RecordsetCat["nome"]?> - <?=$row_RecordsetSubCat["nome"]?></option>
    <?php 
			}while($row_RecordsetSubCat = mysqli_fetch_assoc($RecordsetSubCat)); 
		}
		mysqli_free_result($RecordsetSubCat);
	?>
    <?php 
	}while($row_RecordsetCat = mysqli_fetch_assoc($RecordsetCat)); 
	?>
    </select>
    <br /><small>L'utente verr&agrave; avvisato della pubblicazione di articoli nelle categorie selezionate.</small>
    </p>
<?php
}
mysqli_free_result($RecordsetCat);
?>    
    		
                <p>
                  <label>Ente</label>
                  <input class="text-input medium-input" type="text" id="ente" name="ente" value="<?=$row_Recordset1['ente']?>" />
				  <br /><small>Nome dell'Ente di pubblicazione della Gara</small>
                </p>

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
                    <label>Data di Scadenza (opzionale)</label>
                    
                    <input class="text-input small-input" type="text" id="data_scadenza" name="data_scadenza" readonly="readonly" value="<?php echo str_replace("/","-",data_a_video($row_Recordset1['data_scadenza'])); ?>" />
<script language="JavaScript" type="text/javascript">	
new tcal ({
'formname': 'form1',
'controlname': 'data_scadenza'
});	</script> <small><a href="#cancella" onclick="javascript:document.getElementById('data_scadenza').value='';">cancella</a></small>
                    <br /><small>Utilizzare il calendario a lato della casella</small>
                </p>


				<p>
                  <label>Link al Bando (opzionale)</label>
                  <span id="sprytextfield1">
                  <input class="text-input medium-input" type="text" id="link" name="link" value="<?=$row_Recordset1['link']?>" />
<span class="textfieldInvalidFormatMsg">Formato non valido.</span></span><br /><small>Es: http://www.bandobando.it/pagina.html</small>
                </p>

  				<p>
                	<label>Importo (opzionale)</label>
                  	<input class="text-input small-input" type="text" id="importo" name="importo" value="<?=$row_Recordset1['importo']?>" />
                    <br /><small>Es: 100'000 €</small>
              	</p>
                
                <p>
                	<label>Gi&agrave; comunicato in newsletter</label>
                	<select name="segnalato_newsletter" id="segnalato_newsletter">
                    	<option value="1" <?php if (!(strcmp($row_Recordset1['segnalato_newsletter'],"1"))) {echo "selected=\"selected\"";} ?> >S&igrave;</option>
                        <option value="0" <?php if (!(strcmp($row_Recordset1['segnalato_newsletter'],"0"))) {echo "selected=\"selected\"";} ?> >No</option>
                    </select>
                </p>

                <p>
					<label>La gara è nell'elenco delle Ristrette/Negoziate ?</label>
                	<select name="is_ristretta_negoziata" id="is_ristretta_negoziata">
                    	<option value="1" <?php if (!(strcmp($row_Recordset1['is_ristretta_negoziata'],"1"))) {echo "selected=\"selected\"";} ?> >S&igrave; - Ristretta/Negoziata</option>
                        <option value="0" <?php if (!(strcmp($row_Recordset1['is_ristretta_negoziata'],"0"))) {echo "selected=\"selected\"";} ?> >No - Gara Normale</option>
                    </select>
                    <br /><small>Utilizza questa opzione per spostare la gara dalla sezione normale a quella delle gare su invito (e viceversa)</small>
                </p>

                <p>
                	<label>Mostra online</label>                
                	<input type="checkbox" name="is_attivo" value="1" <?php if (!(strcmp($row_Recordset1['is_attivo'],"1"))) {echo "checked=\"checked\"";} ?> /> mostra o nasconde questo articolo nell'area riservata.
              	</p>	
                <p>
                	<label>Mostra come Archiviato</label>                
                	<input type="checkbox" name="archived" value="1" <?php if (!(strcmp($row_Recordset1['archived'],"1"))) {echo "checked=\"checked\"";} ?> /> Se abilitato, inserisce questo articolo nella sezione "Archivio".
              	</p>	

                <p>
                	<label>Richiedi conferma di Interesse/Non Interesse</label>                
                	<input type="checkbox" name="richiedi_interesse" value="1" <?php if (!(strcmp($row_Recordset1['richiedi_interesse'],"1"))) {echo "checked=\"checked\"";} ?> /> 
               	Seleziona per richiedere la conferma di interesse dei consorziati <strong>e anche per inviare mail di notifica</strong>. </p>	

            
            <p>
                <input class="button" type="submit" value="Aggiorna" />&nbsp;&nbsp;<input class="button" type="button" value="Chiudi" onclick="javascript:location.href='<?php echo $sufx_sezione; ?>_gest.php';" />
            </p>
            </form>			

	
			
            <?php require_once("footer.php"); ?>

			
		</div> <!-- End #insert-content -->
		
	</div>
    <script type="text/javascript">
		var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "url", {isRequired:false});
		var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
    </script>
    </body>
    
</html>
<?php
mysqli_free_result($Recordset1);
mysqli_free_result($rs_lingua);
?>
