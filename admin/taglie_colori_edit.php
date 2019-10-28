<?php 
require_once('../Connections/std_conn.php');
require_once('../funzioni.php');

$MM_authorizedUsers = "1,2,3";
require_once("restrict.php");

$noframe = (($_GET["noframe"]=="1")?1:0);


$sufx_sezione="taglie_colori";
$tabella = "dny_taglia_colore";
$sufx_sezione_padre="prodotti";
$tabella_padre = "dny_prodotto";
$label_id_padre = "id_prodotto";
$padre_get_post = "prodotto";
$id_padre=0;
if( isset($_POST[$padre_get_post]) && ($_POST[$padre_get_post]>0) ){
	$id_padre = $_POST[$padre_get_post];
}else if( isset($_GET[$padre_get_post]) && ($_GET[$padre_get_post]>0) ){
	$id_padre = $_GET[$padre_get_post];
}
if($id_padre<=0) die();


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
	
	$is_attivo = ($_POST['is_attivo']=="1")?1:0;
	$is_attivo_negozi = ($_POST['is_attivo_negozi']=="1")?1:0;	
	$color_code = (strtolower(trim($_POST["color_code"]))!="") ? strtolower(trim($_POST["color_code"])) : "#ffffff";
	$insertSQL = sprintf("UPDATE %s SET 
						 is_attivo=%s, 
						 cod_attr=%s, 
						 taglia=%s, 
						 colore=%s, 
						 color_code=%s, 
						 qta=%s, 
						 extra=%s, 
						 extra_negozi=%s, 
						 is_attivo_negozi=%s
						 WHERE id=%s",
			   $tabella,
			   GetSQLValueString($is_attivo, "int"),
			   GetSQLValueString($_POST["cod_attr"], "text"),
			   GetSQLValueString( strtolower(trim($_POST["taglia"])), "text"),
			   GetSQLValueString( strtolower(trim($_POST["colore"])) , "text"),
			   GetSQLValueString( $color_code, "text"),
			   GetSQLValueString($_POST["qta"], "int"),
			   GetSQLValueString($_POST["extra"], "double"),
			   GetSQLValueString($_POST["extra_negozi"], "double"),
			   GetSQLValueString($is_attivo_negozi, "int"),
			   GetSQLValueString($_POST['id'], "int"));

	mysqli_select_db($std_conn, $database_std_conn);
	$Result1 = mysqli_query($std_conn, $insertSQL) or die(mysqli_error($std_conn));
	$id_inserito = $_POST['id'];


logThis($sufx_sezione, "Modificato", $id_inserito);
header("Location: ".$sufx_sezione."_gest.php?".$padre_get_post."=".$id_padre."&noframe=".$noframe);

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"> 
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title><?php echo $_SESSION["www_title"]; ?> - Modifica Variante Taglia/Colore</title>
		<?php require_once("header.php"); ?>

        <script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
	    <link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
        
	<link rel="stylesheet" href="css/layout.css" type="text/css" />
	<link rel="stylesheet" href="css/colorpicker.css" type="text/css" />
	<script type="text/javascript" src="js/colorpicker.js"></script>
  


</head>
  
<?php if(!$noframe){ ?>		

	<body><div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->
		
		<?php require_once("sidebar_messages.php"); ?>
		
		<div id="insert-content"> <!-- Main Content Section with everything -->
			
            <?php require_once("top_bar.php"); ?>
            
			<?php 
				require_once("breadcrump.php");
				//passo l'id categoria
				breadcrump($sufx_sezione,"edit",$id_padre);
			?>

<?php }else{ 
		echo '<body style="background-image:none;"><div id="body-wrapper"><div id="insert-content" style="background-image:none; margin:3px; padding:0px;">';
	  } ?>

	        <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="nomeform" value="vv" />
                <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>" />
				<input type="hidden" name="<?=$padre_get_post?>" value="<?=$id_padre?>" />                
			<div class="content-box"><!-- Start Content Box -->
				
				<div class="content-box-header">
					
					<h3>Modifica variante Taglia / Colore</h3>
                    <ul class="content-box-tabs">
                        <li><a href="#tab1" class="default-tab">inserimento</a></li>
                    </ul>	
					
    </div> <!-- End .content-box-header -->
					
				<div class="content-box-content">
					<div class="tab-content default-tab" id="tab1"> <!-- This is the target div. id must match the href of this div's tab -->
                    
							<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
							
							  <p>
								<label>Codice variante (opzionale)</label>
								<input class="text-input small-input" type="text" id="cod_attr" name="cod_attr" value="<?=$row_Recordset1["cod_attr"]?>" />
                                <small><strong>Es: Bj48</strong><br />
Se specificato viene riportato nella conferma d'ordine insieme al codice prodotto ed identifica la variante taglia/colore</small>
							  </p>		
                              
							  <p>
								<label>Taglia</label>
								<input class="text-input small-input" type="text" id="taglia" name="taglia" value="<?=$row_Recordset1["taglia"]?>" /> 
                                <small><strong>Es: 48</strong><br />
                                Lasciare vuoto se non è necessario specificare la taglia per questo prodotto.</small>
							  </p>		

							  <p>
								<label>Colore (nome)</label>
								<input class="text-input small-input" type="text" id="colore" name="colore" value="<?=$row_Recordset1["colore"]?>" />
                
                                <small><strong>Es: Beige</strong><br />
                                Si consiglia di indicare il colore in inglese in quanto verrà mostrata questa etichetta a prescidere dalla lingua del cliente finale.<br />
<strong>Lasciando vuoto il NOME del colore, verrà ignorato anche il codice colore selezionato sotto!</strong></small>
							  
                                <div id="customWidget">
                                    <div id="colorSelector2"><div style="background-color: <?=$row_Recordset1["color_code"]?>"></div></div>
                                    <div id="colorpickerHolder2">
                                    </div>
                                </div>
                              <input type="hidden" id="color_code" name="color_code" value="<?=$row_Recordset1["color_code"]?>" />
							  </p>	
                              
                              

							  <p>
								<label>Quantità (magazzino)</label>
								<span id="sprytextfield3">
                                <input class="text-input small-input" type="text" id="qta" name="qta" value="<?=$row_Recordset1["qta"]?>" />
                                <span class="textfieldRequiredMsg">È obbligatorio specificare un valore.</span><span class="textfieldInvalidFormatMsg">Formato non valido.</span><span class="textfieldMinValueMsg">Il valore inserito è minore del valore minimo consentito.</span></span><small><strong>Es: 4</strong><br />
                                Lasciare vuoto se non è necessario specificare la taglia per questo prodotto.</small>
							  </p>
                              


			<div class="content-box column-left">
	            <div class="content-box-header">				
					<h3>E-commerce</h3>
				</div> <!-- End .content-box-header -->
				<div class="content-box-content">
                <div class="invisibile_nascosto">
                <p>
                    <label>Costo Extra</label>
            <span id="sprytextfield1">
            <input class="text-input small-input" type="text" id="extra" name="extra" value="<?=number_format($row_Recordset1["extra"],2,",","")?>" />
            <span class="textfieldRequiredMsg">È obbligatorio specificare un valore.</span><span class="textfieldInvalidFormatMsg">Formato non valido.</span></span>&euro;
                    <br /><small>Es: 12,40 oppure -8,40<br />
Consente di indicare un sovrapprezzo o un sottoprezzo rispetto al valore standard per questa specifica versione di taglia/colore.</small>
                </p>
                </div>		
				<p>
                <label>Mostra online</label>                    
                    <input type="checkbox" name="is_attivo" value="1" <?php if (!(strcmp($row_Recordset1['is_attivo'],"1"))) {echo "checked=\"checked\"";} ?> /> visualizza pubblicamente l'informazione sul web.
                </p>
                </div>	 		
            </div>
			<div class="content-box column-right">
	            <div class="content-box-header">				
					<h3>Area Riservata Negozi</h3>
				</div> <!-- End .content-box-header -->
				<div class="content-box-content">
                <div class="invisibile_nascosto">
                <p>
                    <label>Prezzo</label>
            <span id="sprytextfield2">
            <input class="text-input small-input" id="extra_negozi" name="extra_negozi" value="<?=number_format($row_Recordset1["extra_negozi"],2,",","")?>" />
            <span class="textfieldRequiredMsg">È obbligatorio specificare un valore.</span><span class="textfieldInvalidFormatMsg">Formato non valido.</span></span>&euro;
                    <br /><small>Es: 12,40 oppure -8,40<br />
Consente di indicare un sovrapprezzo o un sottoprezzo rispetto al valore standard per questa specifica versione di taglia/colore.</small>
                </p>
                </div>		
				<p>
                <label>Mostra in area riservata dei negozi</label>
                    
                    <input type="checkbox" name="is_attivo_negozi" value="1" <?php if (!(strcmp($row_Recordset1['is_attivo_negozi'],"1"))) {echo "checked=\"checked\"";} ?> /> 
                    visualizza nell'area riservata dei negozi.
                </p>
                </div>	 		
            </div>
            <div class="clear"></div>
            


                                            
                                <p>
                                <input class="button" type="submit" value="Aggiorna" onclick="javascript:tenta();" />&nbsp;&nbsp;<input class="button" type="button" value="Chiudi" onclick="javascript:location.href='<?php echo $sufx_sezione; ?>_gest.php?<?=$padre_get_post?>=<?=$id_padre?>&noframe=<?=$noframe?>';" />
                    
                        <span id="avviso" class="avviso">Se il click non da nessun effetto, controllare eventuale avvisi scritti in rosso a fianco dei dati inseriti.</span>
                        <script type="text/javascript"><!--//
                        var tentativi
                        tentativi=0;
                        function tenta(){
                            tentativi++;
                            if(tentativi>1){
                                $('#avviso').show();	
                            }
                        }
                        $('#avviso').hide();
                        //--></script>
                                </p>        								
</fieldset>
							
							<div class="clear"></div><!-- End .clear -->
							
						
					</div> <!-- End #tabX -->
				
			  </div> <!-- End .content-box-content -->
			</div> <!-- End .content-box -->
                
                
</form>				

			
<?php if(!$noframe){ ?>			
            <?php require_once("footer.php"); ?>		
<?php } ?>

			
		</div> <!-- End #insert-content -->
		
	</div>
	<script type="text/javascript"><!--//			
			$('#colorpickerHolder2').ColorPicker({
				flat: true,
				color: $('#color_code').val(),
				onSubmit: function(hsb, hex, rgb) {
					$('#colorSelector2 div').css('backgroundColor', '#' + hex);
					$('#color_code').val('#' + hex);
					$('#colorpickerHolder2').stop().animate({height: widt ? 0 : 173}, 500);
					widt = !widt;
				}
			});
			$('#colorpickerHolder2>div').css('position', 'absolute');
			var widt = false;
			$('#colorSelector2').bind('click', function() {
				$('#colorpickerHolder2').stop().animate({height: widt ? 0 : 173}, 500);
				widt = !widt;
			});
	//--></script>   
    <script type="text/javascript">
<!--
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "currency", {format:"dot_comma"});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "currency", {format:"dot_comma"});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "integer", {minValue:0});
//-->
    </script>
      
    </body>
    
</html>
<?php
mysqli_free_result($Recordset1);
?>
