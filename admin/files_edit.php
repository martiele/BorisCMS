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

$colname_Recordset1 = "-1";
if (isset($_GET['idc'])) {
  $colname_Recordset1 = $_GET['idc'];
}
mysqli_select_db($std_conn, $database_std_conn);
$query_Recordset1 = sprintf("SELECT * FROM %s WHERE idc = %s", 
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

$ordinamento=$row_Recordset1["ordinamento"];
if(isset($_POST['old_storia']) && isset($_POST['storia']) && $_POST['old_storia']!=$_POST['storia']){
//Recupero il prossimo num ordinamento
	mysqli_select_db($std_conn, $database_std_conn);
	$query_nextOrdinamento = sprintf("SELECT MAX(ordinamento) massimo FROM %s WHERE is_storia=%s AND eliminato=0",$tabella,GetSQLValueString($_POST["storia"],"int"));
	$nextOrdinamento = mysqli_query($std_conn, $query_nextOrdinamento) or die(mysqli_error($std_conn));
	$row_nextOrdinamento = mysqli_fetch_assoc($nextOrdinamento);
	$totalRows_nextOrdinamento = mysqli_num_rows($nextOrdinamento);
	if($totalRows_nextOrdinamento>0){
		$ordinamento = $row_nextOrdinamento["massimo"] + 1;
	}else{
		$ordinamento = 1;
	}
	mysqli_free_result($nextOrdinamento);

}



if(isset($_POST["nomeform"]) && $_POST["nomeform"]=="vv"){

  $id_inserito = $_POST['id'];

  require("upload_file_sorgente.php");

  $data_attuale=date("Y-m-d H:i:s");
	$insertSQL = sprintf("UPDATE %s SET 
						 id_sezione=%s, nome=%s, descrizione=%s, titolo=%s, sottotitolo=%s, 
foglio = %s, 
codicedoganalepg0 = %s, 
descrizionepg0 = %s, 
schedapaesepg1 = %s, 
riga1pg2 = %s, 
riga2pg2 = %s, 
descrizionepg3 = %s, 
annodapg4 = %s, 
annoapg4 = %s, 
percentualepg5 = %s, 
descrizione1pg6 = %s, 
descrizione2pg6 = %s, 
descrizione3pg6 = %s, 
             ordinamento=%s, is_attivo=%s, modified=%s
						 WHERE idc=%s",
			   $tabella,
			   GetSQLValueString($_POST['id_sezione'], "int"),
         GetSQLValueString($_POST['nome'], "text"),
   			 GetSQLValueString($_POST['descrizione'], "text"),
         GetSQLValueString($_POST['titolo'], "text"),
         GetSQLValueString($_POST['sottotitolo'], "text"),

         GetSQLValueString($_POST['foglio'], "text"),
         GetSQLValueString($_POST['codicedoganalepg0'], "text"),
         GetSQLValueString($_POST['descrizionepg0'], "text"),
         GetSQLValueString($_POST['schedapaesepg1'], "text"),
         GetSQLValueString($_POST['riga1pg2'], "text"),
         GetSQLValueString($_POST['riga2pg2'], "text"),
         GetSQLValueString($_POST['descrizionepg3'], "text"),
         GetSQLValueString($_POST['annodapg4'], "text"),
         GetSQLValueString($_POST['annoapg4'], "text"),
         GetSQLValueString($_POST['percentualepg5'], "text"),
         GetSQLValueString($_POST['descrizione1pg6'], "text"),
         GetSQLValueString($_POST['descrizione2pg6'], "text"),
         GetSQLValueString($_POST['descrizione3pg6'], "text"),

			   GetSQLValueString($ordinamento, "float"),
			   GetSQLValueString((int)$_POST['attivo'], "int"),
         GetSQLValueString($data_attuale, "date"),
			   GetSQLValueString($_POST['id'], "int"));

	mysqli_select_db($std_conn, $database_std_conn);
	$Result1 = mysqli_query($std_conn, $insertSQL) or die(mysqli_error($std_conn));
	$id_inserito = $_POST['id'];

  $stringa_errori_file = "";
  $uploaddir = $_SESSION["path_upload_admin"];

  logThis($sufx_sezione, "Modificato", $id_inserito);

  $generafileoutput = (int)$_POST['generaoutput'];
  if($generafileoutput){
    //richiede $id_inserito
    require("generafile.php");
  }

  header("Location: ".$sufx_sezione."_gest.php");

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"> 
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo $_SESSION["www_title"]; ?> - Modifica Notizia</title>
		<?php require_once("header.php"); ?>

        <script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
	    <link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />

        <!-- TinyMCE - ->
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
        <! -- /TinyMCE -->

        <script type="text/javascript">
          $(document).ready(function($) {
            $("#generadesc").click(function(event) {
              /* Act on the event */
              event.preventDefault();
              cod = $("#codicedoganalepg0").val();
              desc = $("#descrizionepg0").val();
              annoda = $("#annodapg4").val();
              annoa = $("#annoapg4").val();

              //Es: I dati presentati fanno riferimento al periodo 2013-2018 (ultimi 5 anni) e al
              $("#riga1pg2").val("I dati presentati fanno riferimento al periodo "+annoda+"-"+annoa+" (ultimi 5 anni) e al");
              //Es: 2° trimestre 2019 (ultimo trimestre).
              $("#riga2pg2").val("2° trimestre "+annoa+" (ultimo trimestre).");
              //es: Codice Doganale HS 160414 : Preparazioni e conserve di tonni,
              //    palamite e boniti "sarda spp." interi o in pezzi (escl. quelle tritate)
              testo = String("Codice Doganale HS "+cod+" : "+desc);
              testo = testo.replace(/(\r\n|\n|\r)/gm, " ").trim().replace("  ", " ");
              meta = parseInt(testo.length/2);
              console.log(meta);
              pos = testo.indexOf(" ",meta);
              console.log(pos);
              testo = testo.substr(0,pos).trim()+"\n"+testo.substr(pos+1).trim();

              $("#descrizionepg3").val(testo);
              
            });
          });
        </script>
        <style type="text/css">
          input#generadesc {
              background-color: green;
              color: white;
              padding: 10px 20px;
              margin-right: 20px;
              border: 1px solid white;
          }
        </style>

</head>
  
	<body><div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->
		
		<?php require_once("sidebar_messages.php"); ?>
		
		<div id="insert-content"> <!-- Main Content Section with everything -->
			
            <?php require_once("top_bar.php"); ?>

	        <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="nomeform" value="vv" />
                <input type="hidden" name="id" value="<?php echo $_GET["idc"]; ?>" />
                
            <p>
               <label>Nome Esportazione (uso interno)</label>
               <span id="sprytextfield1">
               <input class="text-input medium-input" type="text" id="nome" name="nome" value="<?=$row_Recordset1["nome"]?>" />
               <span class="textfieldRequiredMsg">È obbligatorio specificare un valore.</span></span>
            </p>		

			      <p>
               <label>Modello di Output</label>
               <span id="sprytextfield1">
               <!--<input class="text-input medium-input" type="text" id="id_sezione" name="id_sezione" value="<?=$arr_sezioni[$row_Recordset1['id_sezione']]?>" />-->
               <select name="id_sezione" value="<?=$row_Recordset1['id_sezione']?>">
               		<?php
               		foreach ($arr_sezioni as $key => $value) {
               		?>
               		<option value="<?=$key?>" <?php if($key==$row_Recordset1['id_sezione']) { ?> selected <?php } ?> ><?=$key?> - <?=$value?></option>
 
               		<?php
               		}
               		?>
               </select>
               <span class="textfieldRequiredMsg">È obbligatorio specificare un valore.</span></span>
           	</p>

            <p>
              <label>File Sorgente Dati (Excel)</label>
               <?php 
                $filetolink = $_SESSION["path_upload_admin"] . $_SESSION["path_filecaricati"] . $row_Recordset1["fileimg"];
                if( (strcmp($row_Recordset1["fileimg"],NULL)!=0) && (file_exists($filetolink)) ){
               ?>
                  <a title="Scarica il file" target="blank" href="<?=$filetolink?>" /><?=$row_Recordset1["fileimg"]?></a>
               <?php }else{ ?>
          <label> Nessun file selezionato </label>
         <?php }?>
                <br /><br /> <span> Modifica file sorgente:</span> 
               <input type="file" name="fileimg" id="fileimg" />    
                <small id="smallText">Inserisci un nuovo file xls o xlxs come sorgente dati</small>               
               <br /><br /><span>Rimuovi file corrente: <input type="checkbox" id="removeImg" name="remove_img" value="1"/>    </span>
              </p>

<hr>

<h3>Sorgente dati per Scheda Paese</h3>

            <p>
               <label>Foglio sorgente</label> 
               <select class="text-input small-input" id="foglio" name="foglio" >
                <?php for($i=1;$i<=30;$i++){ 
                  $numero = "scheda".sprintf("%02d", $i);
                  ?>
                   <option value="<?=$numero?>" <?php if($key==$row_Recordset1['foglio']) { ?> selected <?php } ?>><?=$numero?></option>
                <?php } ?>
               </select> <small>Seleziona la scheda dati da cui attingere</small>
            </p>


<hr>
<h3>Pagina 0</h3>
            <p>
               <label>Codice Doganale</label>
               <input class="text-input medium-input" type="text" id="codicedoganalepg0" name="codicedoganalepg0" value="<?=$row_Recordset1["codicedoganalepg0"]?>" placeholder="160414" />
               <small>Es: 160414</small>
            </p>
            <p>
               <label>Descrizione breve</label>
               <textarea rows="3" class="text-input medium-input" type="text" id="descrizionepg0" name="descrizionepg0" placeholder="Preparazioni e conserve di tonni, palamite e boniti
'sarda spp.' interi o in pezzi (escl. quelle tritate)"><?=$row_Recordset1["descrizionepg0"]?></textarea>
               <small>es: Preparazioni e conserve di tonni, palamite e boniti 
"sarda spp." interi o in pezzi (escl. quelle tritate)</small>
            </p>
<hr>
<h3>Worlddata, CountryData e WorldExport</h3>
            <p>
               <label>Anno Da</label>
               <input class="text-input small-input" type="text" id="annodapg4" name="annodapg4" value="<?=$row_Recordset1["annodapg4"]; ?>" placeholder="2013" /> 
               <small>Es: 2013</small>
            </p>
            <p>
               <label>Anno A</label>
               <input class="text-input small-input" type="text" id="annoapg4" name="annoapg4" value="<?=$row_Recordset1["annoapg4"]; ?>" placeholder="2018" /> 
               <small>Es: 2018</small>
            </p>
<hr>

<h3>Genera descrizioni</h3>
            <p>
               <input type="button" id="generadesc" value="Genera descrizioni" /> 
               <small>genera i casmpi sottostanti</small>
            </p>
            
<hr>

<!--
<h3>Indice</h3>
            <p>
               <label>Scheda Paese</label>
               <input class="text-input small-input" type="text" id="schedapaesepg1" name="schedapaesepg1" value="<?=$row_Recordset1["schedapaesepg1"]?>" placeholder="Germania" /> 
               <small>Es: Germania</small>
            </p>
<hr>
-->
<h3>Premessa</h3>
            <p>
               <label>Riga 1</label>
               <input class="text-input medium-input" type="text" id="riga1pg2" name="riga1pg2" value="<?php echo $row_Recordset1["riga1pg2"]!="" ? $row_Recordset1["riga1pg2"] : ""; ?>" placeholder="I dati presentati fanno riferimento al periodo 2013-2018 (ultimi 5 anni) e al" /> 
               <small>Es: I dati presentati fanno riferimento al periodo 2013-2018 (ultimi 5 anni) e al</small>
            </p>
            <p>
               <label>Riga 2</label>
               <input class="text-input medium-input" type="text" id="riga2pg2" name="riga2pg2" value="<?php echo $row_Recordset1["riga2pg2"]!="" ? $row_Recordset1["riga2pg2"] : ""; ?>" placeholder="2° trimestre 2019 (ultimo trimestre)." /> 
               <small>Es: 2° trimestre 2019 (ultimo trimestre).</small>
            </p>
<hr>
<h3>Cover</h3>
            <p>
               <label>Descrizione cover</label>
               <textarea rows="3" class="text-input medium-input" type="text" id="descrizionepg3" name="descrizionepg3" placeholder="Codice Doganale HS 160414 : Preparazioni e conserve di tonni,
palamite e boniti 'sarda spp.' interi o in pezzi (escl. quelle tritate)"><?php echo $row_Recordset1["descrizionepg3"]!="" ? $row_Recordset1["descrizionepg3"] : ""; ?></textarea>
               <small>es: Codice Doganale HS 160414 : Preparazioni e conserve di tonni,
palamite e boniti "sarda spp." interi o in pezzi (escl. quelle tritate)</small>
            </p>
<hr>



<!--
<h3>Ilquadlegenda</h3>
            <p>
               <label>Percentuale</label>
               <input class="text-input small-input" type="text" id="percentualepg5" name="percentualepg5" value="<?=$row_Recordset1["percentualepg5"]; ?>" placeholder="+9,2%" /> 
               <small>Es: +9,2%</small>
            </p>
<hr>
-->

<h3>Scheda Leg 1</h3>
            <p>
<label>Descrizione scheda legenda 1</label>
<textarea rows="10" class="text-input medium-input" type="text" id="descrizione1pg6" name="descrizione1pg6" placeholder="La Germania importa il prodotto selezionato per un valore pari a 519,6 milioni di Euro, posizionandosi come 1° paese importatore a livello mondiale (13,6% del totale). Lo score nella classifica dei mercati OPPORTUNITA’ è complessivamente positivo (798).  

L’Italia esporta verso la Germania 8,9 milioni di Euro, rappresenta quindi il 2° mercato per l’export italiano del prodotto selezionato. Nel Paese è diretto il 18,1% del prodotto complessivo esportato dall’Italia e l’1,7% del prodotto importato è di provenienza italiana. In relazione a 100 euro di importazioni, la Germania esporta 202 Euro verso il mondo, si tratta quindi di un Paese che esporta il doppio di quanto importa. Per quanto riguarda l’Italia, la cifra relativa alle esportazioni verso il nostro Paese è 854 Euro ogni 100 Euro importati, 8,5 volte superiore."><?php echo $row_Recordset1["descrizione1pg6"]!="" ? $row_Recordset1["descrizione1pg6"] : 
"La Germania importa il prodotto selezionato per un valore pari a 519,6 milioni di Euro, posizionandosi come 1° paese importatore a livello mondiale (13,6% del totale). Lo score nella classifica dei mercati OPPORTUNITA’ è complessivamente positivo (798).  

L’Italia esporta verso la Germania 8,9 milioni di Euro, rappresenta quindi il 2° mercato per l’export italiano del prodotto selezionato. Nel Paese è diretto il 18,1% del prodotto complessivo esportato dall’Italia e l’1,7% del prodotto importato è di provenienza italiana. In relazione a 100 euro di importazioni, la Germania esporta 202 Euro verso il mondo, si tratta quindi di un Paese che esporta il doppio di quanto importa. Per quanto riguarda l’Italia, la cifra relativa alle esportazioni verso il nostro Paese è 854 Euro ogni 100 Euro importati, 8,5 volte superiore."; ?></textarea>
<br><small>Esempio:<br />
La Germania importa il prodotto selezionato per un valore pari a 519,6 milioni di Euro, posizionandosi come 1° paese importatore a livello mondiale (13,6% del totale). Lo score nella classifica dei mercati OPPORTUNITA’ è complessivamente positivo (798).  

L’Italia esporta verso la Germania 8,9 milioni di Euro, rappresenta quindi il 2° mercato per l’export italiano del prodotto selezionato. Nel Paese è diretto il 18,1% del prodotto complessivo esportato dall’Italia e l’1,7% del prodotto importato è di provenienza italiana. In relazione a 100 euro di importazioni, la Germania esporta 202 Euro verso il mondo, si tratta quindi di un Paese che esporta il doppio di quanto importa. Per quanto riguarda l’Italia, la cifra relativa alle esportazioni verso il nostro Paese è 854 Euro ogni 100 Euro importati, 8,5 volte superiore.</small>
            </p>


<h3>Scheda Leg 2</h3>
            <p>
<label>Descrizione scheda legenda 2</label>
<textarea rows="10" class="text-input medium-input" type="text" id="descrizione2pg6" name="descrizione2pg6" placeholder="Nella terza sezione sono riportate le variazioni delle importazioni dal mondo e dall’Italia. Le importazioni della Germania dal Mondo dal 2013 al 2018 sono aumentate del 5,8% di media all’anno, mentre quelle dall’Italia sono cresciute del 38,9%. Ancora, è stato registrato un trend del +00,00% nell'ultimo anno, del +00,00% nell'ultimo trimestre con un trend ponderato complessivo del +00,00%.

Nella quarta sezione si evince che la crescita dell’export mondiale verso la Germania è inferiore rispetto al trend di crescita medio. Al contrario la crescita delle esportazioni italiane del prodotto selezionato verso il Paese, è maggiore rispetto al trend medio dell’export italiano verso il mondo. Non sono presenti dazi per chi esporta il prodotto selezionato in Germania, né dall’Italia né dall’estero. Le previsioni a breve termine riportano un mercato che continuerà a crescere in maniera significativa."><?php echo $row_Recordset1["descrizione2pg6"]!="" ? $row_Recordset1["descrizione2pg6"] : 
"Nella terza sezione sono riportate le variazioni delle importazioni dal mondo e dall’Italia. Le importazioni della Germania dal Mondo dal 2013 al 2018 sono aumentate del 5,8% di media all’anno, mentre quelle dall’Italia sono cresciute del 38,9%. Ancora, è stato registrato un trend del +00,00% nell'ultimo anno, del +00,00% nell'ultimo trimestre con un trend ponderato complessivo del +00,00%.

Nella quarta sezione si evince che la crescita dell’export mondiale verso la Germania è inferiore rispetto al trend di crescita medio. Al contrario la crescita delle esportazioni italiane del prodotto selezionato verso il Paese, è maggiore rispetto al trend medio dell’export italiano verso il mondo. Non sono presenti dazi per chi esporta il prodotto selezionato in Germania, né dall’Italia né dall’estero. Le previsioni a breve termine riportano un mercato che continuerà a crescere in maniera significativa."; ?></textarea>
<br><small>Esempio: 
Nella terza sezione sono riportate le variazioni delle importazioni dal mondo e dall’Italia. Le importazioni della Germania dal Mondo dal 2013 al 2018 sono aumentate del 5,8% di media all’anno, mentre quelle dall’Italia sono cresciute del 38,9%. Ancora, è stato registrato un trend del +00,00% nell'ultimo anno, del +00,00% nell'ultimo trimestre con un trend ponderato complessivo del +00,00%.

Nella quarta sezione si evince che la crescita dell’export mondiale verso la Germania è inferiore rispetto al trend di crescita medio. Al contrario la crescita delle esportazioni italiane del prodotto selezionato verso il Paese, è maggiore rispetto al trend medio dell’export italiano verso il mondo. Non sono presenti dazi per chi esporta il prodotto selezionato in Germania, né dall’Italia né dall’estero. Le previsioni a breve termine riportano un mercato che continuerà a crescere in maniera significativa.</small>
            </p>


<h3>Scheda Leg 3</h3>
            <p>
<label>Descrizione scheda legenda 3</label>
<textarea rows="10" class="text-input medium-input" type="text" id="descrizione3pg6" name="descrizione3pg6" placeholder="La quinta e ultima sezione riporta il valore medio unitario, ovvero un chilo di prodotto italiano esportato in Germania ha un valore medio di 17,21 Euro, inferiore rispetto alla media di 28,60 Euro delle importazioni dagli altri Paesi. La Germania è un mercato assolutamente sicuro, sia dal punto di vista della sicurezza paese che di sicurezza del credito.

Infine la Germania risulta essere un mercato DEFENSE per l’Italia, ovvero si tratta di un Paese molto dinamico per l’Italia, meno per il mondo. La crescita quindi, evidenziata in precedenza, si prevede proseguirà nei prossimi anni."><?php echo $row_Recordset1["descrizione3pg6"]!="" ? $row_Recordset1["descrizione3pg6"] : 
"Nella terza sezione sono riportate le variazioni delle importazioni dal mondo e dall’Italia.
Le importazioni della Germania dal Mondo dal 2013 al 2018 sono aumentate del 5,8%
di media all’anno, mentre quelle dall’Italia sono cresciute del 38,9%. Ancora, è stato 
registrato un trend del +00,00% nell'ultimo anno, del +00,00% nell'ultimo trimestre
con un trend ponderato complessivo del +00,00%.
"; ?></textarea>
<br><small>Esempio: 
La quinta e ultima sezione riporta il valore medio unitario, ovvero un chilo di prodotto italiano esportato in Germania ha un valore medio di 17,21 Euro, inferiore rispetto alla media di 28,60 Euro delle importazioni dagli altri Paesi. La Germania è un mercato assolutamente sicuro, sia dal punto di vista della sicurezza paese che di sicurezza del credito.

Infine la Germania risulta essere un mercato DEFENSE per l’Italia, ovvero si tratta di un Paese molto dinamico per l’Italia, meno per il mondo. La crescita quindi, evidenziata in precedenza, si prevede proseguirà nei prossimi anni.</small>
            </p>
<hr>

<h3>Altre impostazioni</h3>


            <!--
            <p>
                <label>(Ri)Genera output</label>
                <input type="radio" name="generaoutput" value="1" checked="checked">S&Igrave; &nbsp;&nbsp;&nbsp;
                <input type="radio" name="generaoutput" value="0"/>No
                <br />
            </p>
          -->
          <input type="hidden" name="generaoutput" value="0">


          <input type="hidden" name="attivo" value="1">
          <!--
            <p>
                <label>E' attivo?</label>
                <input type="radio" id="attivoSi" name="attivo" value="1"  <?php if($row_Recordset1["is_attivo"]==1) echo 'checked="checked"' ?>/>S&Igrave; &nbsp;&nbsp;&nbsp;
                <input type="radio" id="attivoNo" name="attivo" value="0"  <?php if($row_Recordset1["is_attivo"]==0) echo 'checked="checked"' ?>/>No
                <br />
          	</p>
          -->

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
