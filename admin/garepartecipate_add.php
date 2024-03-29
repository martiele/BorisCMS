<?php 
require_once('../Connections/std_conn.php');
require_once('../funzioni.php');

$MM_authorizedUsers = "1,2,3";
require_once("restrict.php");

//serve per i redirect e i link. "garepartecipate_gest.php"
$sufx_sezione="garepartecipate";
$tabella = "dny_garepartecipate";

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if($_POST["nomeform"]=="vv"){
	
	//Recupero il prossimo num ordinamento
	mysqli_select_db($std_conn, $database_std_conn);
	$query_nextOrdinamento = sprintf("SELECT MAX(N) massimo FROM %s",$tabella);
	$nextOrdinamento = mysqli_query($std_conn, $query_nextOrdinamento) or die(mysqli_error($std_conn));
	$row_nextOrdinamento = mysqli_fetch_assoc($nextOrdinamento);
	$totalRows_nextOrdinamento = mysqli_num_rows($nextOrdinamento);
	if($totalRows_nextOrdinamento>0){
		$ordinamento = $row_nextOrdinamento["massimo"] + 1;
	}else{
		$ordinamento = 1;
	}
	mysqli_free_result($nextOrdinamento);

	$is_attivo = ($_POST['is_attivo'.$i]=="1")?1:0;		
	$insertSQL = sprintf("INSERT INTO %s (EnteAppaltante, FormaDiPartecipazione, ConsorziateAffidatarie, Attivita, ScadenzaPresOfferta, ImportoAppalto, OffertaTecnica, AggiudicazioneDefinitiva, DataGara, RibassoOfferto, RibassoAggiudicatario, PunteggioCLS, PunteggioAggiudicatario, N, is_attivo) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
			   $tabella,
			   GetSQLValueString($_POST['EnteAppaltante'], "text"),
			   GetSQLValueString($_POST['FormaDiPartecipazione'], "text"),
			   GetSQLValueString($_POST['ConsorziateAffidatarie'], "text"),
			   GetSQLValueString($_POST['Attivita'], "text"),
			   GetSQLValueString($_POST['ScadenzaPresOfferta'], "text"),
			   GetSQLValueString($_POST['ImportoAppalto'], "text"),
			   GetSQLValueString($_POST['OffertaTecnica'], "text"),
			   GetSQLValueString($_POST['AggiudicazioneDefinitiva'], "text"),
			   GetSQLValueString($_POST['DataGara'], "text"),
			   GetSQLValueString($_POST['RibassoOfferto'], "text"),
			   GetSQLValueString($_POST['RibassoAggiudicatario'], "text"),
			   GetSQLValueString($_POST['PunteggioCLS'], "text"),
			   GetSQLValueString($_POST['PunteggioAggiudicatario'], "text"),
			   GetSQLValueString($ordinamento, "int"),
			   GetSQLValueString($is_attivo, "int"));

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
		<title><?php echo $_SESSION["www_title"]; ?> - Aggiungi Gara Partecipata</title>
		<?php require_once("header.php"); ?>
        <script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
	    <link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />


</head>
  
	<body><div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->
		
		<?php require_once("sidebar_messages.php"); ?>
		
		<div id="insert-content"> <!-- Main Content Section with everything -->
			
            <?php require_once("top_bar.php"); ?>

			
			<div class="content-box"><!-- Start Content Box -->
				
				<div class="content-box-header">
					
					<h3>Aggiunta Gara Partecipata</h3>
					
					<ul class="content-box-tabs">
						<li><a href="#tab1" class="default-tab">Aggiunta Gara Partecipata</a></li>
					</ul>
					
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
					
					<div class="tab-content default-tab" id="tab1"> <!-- This is the target div. id must match the href of this div's tab -->
                    
						
					  <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data">
       	<input type="hidden" name="nomeform" value="vv" />
							
							<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
								
								
							  <p>
								<label>Ente Appaltante</label>
								<span id="sprytextfield1">
								<input class="text-input medium-input" type="text" id="EnteAppaltante" name="EnteAppaltante" />
								<span class="textfieldRequiredMsg input-notification error png_bg">� obbligatorio specificare un valore.</span></span>
                                <br /><small>Es: Comune di Empoli</small>
							  </p>	                              

							  <p>
								<label>Forma di Partecipazione</label>
                                <input type="text" class="text-input medium-input" id="FormaDiPartecipazione" name="FormaDiPartecipazione"  />				
                                <br /><small>Es: Consorzio Leonardo Servizi</small>
							  </p>	 

							  <p>
								<label>Consorziate Affidatarie</label>
                                <input type="text" class="text-input medium-input" id="ConsorziateAffidatarie" name="ConsorziateAffidatarie" />				
                                <br /><small>Es: Az Idrovie Srl</small>
							  </p>	 

							  <p>
								<label>Attivit&agrave;</label>
                                <input type="text" class="text-input medium-input" id="Attivita" name="Attivita" />				
                                <br /><small>Es: <em><strong>Servizi</strong></em> oppure <em><strong>Lavori</strong></em></small>
							  </p>	 

							  <p>
								<label>Scadenza Presentazione Offerta</label>
                                <input type="text" class="text-input small-input" id="ScadenzaPresOfferta" name="ScadenzaPresOfferta" />				
                                <br /><small>Es: 03/01/2014</small>
							  </p>	 

							  <p>
								<label>Importo Appalto</label>
                                <textarea class="text-input medium-input" id="ImportoAppalto" name="ImportoAppalto"></textarea>				
                                <br /><small>Es: &euro; 360.000,00</small>
							  </p>	 

							  <p>
								<label>Offerta Tecnica (SI/NO)</label>
                                <input type="text" class="text-input small-input" id="OffertaTecnica" name="OffertaTecnica" />				
                                <br /><small>Es: <strong><em>SI</em></strong> oppure <strong><em>NO</em></strong></small>
							  </p>	
                              
							  <p>
								<label>Aggiudicazione Definitiva</label>
                                <textarea class="text-input medium-input" id="AggiudicazioneDefinitiva" name="AggiudicazioneDefinitiva"></textarea>						
                                <br /><small>Es: imp. Ing. G. Lupi srl</small>
							  </p>	 


							  <p>
								<label>Data Gara</label>
                                <input type="text" class="text-input small-input" id="DataGara" name="DataGara" />
                                <br /><small>Es: SI* non part.</small>
							  </p>		

							  <p>
								<label>Ribasso offerto</label>
                                <textarea class="text-input medium-input" id="RibassoOfferto" name="RibassoOfferto"></textarea>				
                                <br /><small>Es: 11,21%</small>
							  </p>	 

							  <p>
								<label>Ribasso aggiudicatario</label>
                                <textarea class="text-input medium-input" id="RibassoAggiudicatario" name="RibassoAggiudicatario"></textarea>	
                                <br /><small>Es: 69,30%</small>
							  </p>	 

							  <p>
								<label>Punteggio CLS</label>
                                <textarea class="text-input medium-input" id="PunteggioCLS" name="PunteggioCLS"></textarea>	
                                <br /><small>Es: 72,92%</small>
							  </p>	 

							  <p>
								<label>Punteggio aggiudicatario</label>
                                <textarea class="text-input medium-input" id="PunteggioAggiudicatario" name="PunteggioAggiudicatario"></textarea>	
                                <br /><small>Es: 87,41%</small>
							  </p>	                               
                
                            <p>
                                <label>Mostra online</label>                
                                <input type="checkbox" name="is_attivo" value="1" checked="checked" /> mostra o nasconde questo articolo nell'area riservata.
                            </p>	
		                              
							
								
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
//-->
    </script>
	</body>
  
</html>
