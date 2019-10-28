<?php 
require_once('../Connections/std_conn.php');
require_once('../funzioni.php');

$MM_authorizedUsers = "1,2,3";
require_once("restrict.php");

//serve per i redirect e i link. "manifinteresse_gest.php"
$sufx_sezione="manifinteresse";
$tabella = "dny_manifestazioniinteresse";

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
	$insertSQL = sprintf("INSERT INTO %s (id_sottocategoria, EnteAppaltante, OggettoGara, Scad, SiNo, N, is_attivo, consorziate_indicate, link_gara) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
			   $tabella,
			   GetSQLValueString($_POST['id_sottocategoria'], "int"),
			   GetSQLValueString(htmlentities($_POST['EnteAppaltante']), "text"),
			   GetSQLValueString(htmlentities($_POST['OggettoGara']), "text"),
			   GetSQLValueString($_POST['Scad'], "text"),
			   GetSQLValueString($_POST['SiNo'], "text"),
			   GetSQLValueString($ordinamento, "int"),
			   GetSQLValueString($is_attivo, "int"),
			   GetSQLValueString($_POST['consorziate_indicate'], "text"),
			   GetSQLValueString($_POST['link_gara'], "text"));

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
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?php echo $_SESSION["www_title"]; ?> - Aggiungi Manifestazione di Interesse</title>
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
					
					<h3>Aggiunta Manifestazione di Interesse</h3>
					
					<ul class="content-box-tabs">
						<li><a href="#tab1" class="default-tab">Aggiunta Manifestazione di Interesse</a></li>
					</ul>
					
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
					
					<div class="tab-content default-tab" id="tab1"> <!-- This is the target div. id must match the href of this div's tab -->
                    
						
					  <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data">
       	<input type="hidden" name="nomeform" value="vv" />
							
							<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->


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
    	<option value="0">----------------</option>
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
    		<option value="<?=$row_RecordsetSubCat["id"]?>"><?=$row_RecordsetCat["nome"]?> - <?=$row_RecordsetSubCat["nome"]?></option>
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
								<label>Ente Appaltante</label>
								<span id="sprytextfield1">
								<input class="text-input medium-input" type="text" id="EnteAppaltante" name="EnteAppaltante" />
								<span class="textfieldRequiredMsg input-notification error png_bg">È obbligatorio specificare un valore.</span></span>
                                <br /><small>Es: Nome Ente Appaltante</small>
							  </p>		

							  <p>
								<label>Oggetto Gara</label>
                                <textarea class="text-input medium-input" id="OggettoGara" name="OggettoGara"></textarea>						
                                <br /><small>Es: Lavori di manutenzione delle strade comunali per l'anno 2013</small>
							  </p>	 

							  <p>
								<label>Scadenza</label>
                                <input type="text" class="text-input small-input" id="Scad" name="Scad" />				
                                <br /><small>Es: 01 GEN - 13:00</small>
							  </p>	 

							  <p>
								<label>Esito</label>
                                <input type="text" class="text-input small-input" id="SiNo" name="SiNo" />
                                <br /><small>Es: SI* non part.</small>
							  </p>	 

							  <p>
								<label>Consorziate Indicate</label>
                                <input type="text" class="text-input medium-input" id="consorziate_indicate" name="consorziate_indicate" value="" />
							  </p>	 


							  <p>
								<label>Link alla Gara nella sezione Ristrette/Negoziate</label>
									<?php
										mysqli_select_db($std_conn, $database_std_conn);
										$query_Recordset2 = sprintf("SELECT A.*, B.nome FROM (%s as A LEFT JOIN %s as B ON A.id = B.id_ref) WHERE A.deleted=0 AND (B.id_lingua=%s OR B.id_lingua is Null) AND (A.is_ristretta_negoziata=1) ORDER BY A.ordinamento DESC", 
															"dny_news",
															"dny_news_lingua",
															GetSQLValueString($_SESSION["linguadefault"],"int"));
										$Recordset2 = mysqli_query($std_conn, $query_Recordset2) or die(mysqli_error($std_conn));
										$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
										$totalRows_Recordset2 = mysqli_num_rows($Recordset2);
										if($totalRows_Recordset2>0){
										?>
											<script type="text/javascript">
												jQuery(document).ready(function($) {
													$("#garalink").change(function(event) {
													    $("#link_gara").val($( "select option:selected" ).val());
													});
												});
											</script>
											Cerca tra le gare: 
											<select id="garalink" class="text-input small-input">
												<option value="">-- cerca tra le gare ristrette/negoziate -- </option>
										<?php
											do{
												echo '<option value="'.$_SESSION["globalCompleteUrl"]."/area-riservata-articolo/?ida=".$row_Recordset2["id"].'">'.$row_Recordset2["progressivo"]." - ".$row_Recordset2["nome"].'</option>';
											}while($row_Recordset2 = mysqli_fetch_assoc($Recordset2));
										?>
											</select> per generare automaticamente il link:
			                                <br /><br />
										<?php
										}
										mysqli_free_result($Recordset2);
									?>
                                <input type="text" class="text-input medium-input" id="link_gara" name="link_gara" value="" placeholder="http://" />
                                
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
