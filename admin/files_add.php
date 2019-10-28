<?php 
require_once('../Connections/std_conn.php');
require_once('../funzioni.php');

@include 'photo.php'; 

$MM_authorizedUsers = "1,2,3";
require_once("restrict.php");

$sufx_sezione="files";
$tabella = "dny_file_generati";

mysqli_select_db($std_conn, $database_std_conn);
$query_sezioni = "SELECT * FROM dny_modelli WHERE eliminato=0 AND is_attivo=1 ORDER BY ids DESC";
$var_sezioni = mysqli_query($std_conn, $query_sezioni) or die(mysqli_error($std_conn));
while($array_sezioni = mysqli_fetch_assoc($var_sezioni)){
	$arr_sezioni[$array_sezioni["ids"]] = $array_sezioni["nome"]; 
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


if(isset($_POST["nomeform"]) && $_POST["nomeform"]=="vv"){
	
	//Recupero il prossimo num ordinamento
	mysqli_select_db($std_conn, $database_std_conn);
	$query_nextOrdinamento = sprintf("SELECT MAX(ordinamento) massimo FROM %s WHERE eliminato=0 ",$tabella);
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
	$insertSQL = sprintf("INSERT INTO %s (ordinamento, id_sezione, nome, descrizione, titolo, sottotitolo, is_attivo, modified, created) VALUES (%s, %s,%s, %s,%s,%s,%s,%s,%s)",
			   $tabella,
			   GetSQLValueString($ordinamento, "float"),
			   GetSQLValueString($_POST["id_sezione"], "int"),
			   GetSQLValueString($_POST["nome"], "text"),
			   GetSQLValueString($_POST["descrizione"], "text"),
			   GetSQLValueString($_POST["titolo"], "text"),
			   GetSQLValueString($_POST["sottotitolo"], "text"),
			   GetSQLValueString((int)$_POST["attivo"], "int"),
         GetSQLValueString($data_attuale, "date"),
         GetSQLValueString($data_attuale, "date"));        
			   
	mysqli_select_db($std_conn, $database_std_conn);
	$Result1 = mysqli_query($std_conn, $insertSQL) or die(mysqli_error($std_conn));
	$id_inserito = mysqli_insert_id($std_conn);
		
		
	require("upload_file_sorgente.php");


	logThis($sufx_sezione, "Aggiunto", $id_inserito);
	if(isset($_POST['storia']) && $_POST['storia']==1){
		$sufx_sezione.='_storia';
	}
	header("Location: ".$sufx_sezione."_edit.php?idc=".$id_inserito);

}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"> 
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title><?php echo $_SESSION["www_title"]; ?> - Aggiungi Notizia</title>
		<?php require_once("header.php"); ?>
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
      plugins : "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
    
	// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,ltr,rtl",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak",
      theme_advanced_toolbar_location : "top",
      theme_advanced_toolbar_align : "left",
      theme_advanced_statusbar_location : "bottom",
      theme_advanced_resizing : true,
        
    });
        </script>
        <!-- /TinyMCE -->
        
</head>
  
	<body><div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->
		
		<?php require_once("sidebar_messages.php"); ?>
		
		<div id="insert-content"> <!-- Main Content Section with everything -->
			
            <?php require_once("top_bar.php"); ?>

        	<form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data">
       		<input type="hidden" name="nomeform" value="vv" />
	

              <p>
               <label>Nome Esportazione (uso interno)</label>
               <span id="sprytextfield1">
               <input class="text-input medium-input" type="text" id="nome" name="nome" />
               <span class="textfieldRequiredMsg">È obbligatorio specificare un valore.</span></span>
            </p>


                <p>
               <label>Sezione</label>
               <span id="sprytextfield1">
               <select name="id_sezione" value="<?=$row_Recordset1['id_sezione']?>">
               		<?php
               		foreach ($arr_sezioni as $key => $value) {
               		?>
               		<option value="<?=$key?>" ><?=$key?> - <?=$value?></option>
 
               		<?php
               		}
               		?>
               </select>
               <span class="textfieldRequiredMsg">È obbligatorio specificare un valore.</span></span>
           	</p>

            <p>
              <label>File Sorgente Dati (Excel)</label>
              <span> Seleziona file sorgente:</span> 
               <input type="file" name="fileimg" id="fileimg" />    
                <small id="smallText">Inserisci un nuovo file xls o xlxs come sorgente dati</small>   
                              
            </p>



          <input type="hidden" name="attivo" value="1">
          <!--
            <p>
                    <label>E' attivo?</label>
                    <input type="radio" id="attivoSi" name="attivo" value="1" checked="checked" />S&Igrave; &nbsp;&nbsp;&nbsp;
                    <input type="radio" id="attivoNo" name="attivo" value="0"  <?php if($row_Recordset1["is_attivo"]==0) echo 'checked="checked"' ?>/>No
                    <br />
          	</p>
          -->
            		
                
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