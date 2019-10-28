<?php 
require_once('../Connections/std_conn.php');
require_once('../funzioni.php');

$MM_authorizedUsers = "1,2,3";
require_once("restrict.php");

$sufx_sezione="lookbook";
$tabella = "dny_lookbook";

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

mysqli_select_db($std_conn, $database_std_conn);
$query_rs_lingua = "SELECT * FROM dny_lingua WHERE deleted = 0 ORDER BY ordinamento ASC";
$rs_lingua = mysqli_query($std_conn, $query_rs_lingua) or die(mysqli_error($std_conn));
$row_rs_lingua = mysqli_fetch_assoc($rs_lingua);
$totalRows_rs_lingua = mysqli_num_rows($rs_lingua);
if($totalRows_rs_lingua<=0){
	die();
}


if($_POST["nomeform"]=="vv"){
	
	//Recupero il prossimo num ordinamento
	mysqli_select_db($std_conn, $database_std_conn);
	$query_nextOrdinamento = sprintf("SELECT MAX(ordinamento) massimo FROM %s",
		$tabella);
	$nextOrdinamento = mysqli_query($std_conn, $query_nextOrdinamento) or die(mysqli_error($std_conn));
	$row_nextOrdinamento = mysqli_fetch_assoc($nextOrdinamento);
	$totalRows_nextOrdinamento = mysqli_num_rows($nextOrdinamento);
	if($totalRows_nextOrdinamento>0){
		$ordinamento = $row_nextOrdinamento["massimo"] + 1;
	}else{
		$ordinamento = 1;
	}
	mysqli_free_result($nextOrdinamento);
		
	$is_attivo = ($_POST['is_attivo']=="1")?1:0;
	$is_attivo_negozi = ($_POST['is_attivo_negozi']=="1")?1:0;
	$insertSQL = sprintf("INSERT INTO %s (ordinamento, is_attivo, created, modified, is_attivo_negozi) VALUES (%s, %s, %s, %s, %s)",
			   $tabella,
			   GetSQLValueString($ordinamento, "int"),
			   GetSQLValueString($is_attivo, "int"),
			   GetSQLValueString(data_a_database(date("d/m/Y")),"date"),
			   GetSQLValueString(data_a_database(date("d/m/Y")),"date"),
			   GetSQLValueString($is_attivo_negozi, "int"));
	mysqli_select_db($std_conn, $database_std_conn);
	$Result1 = mysqli_query($std_conn, $insertSQL) or die(mysqli_error($std_conn));
	$id_inserito = mysqli_insert_id($std_conn);
	
	do{ 
		$i = $row_rs_lingua["id"];
		$nome = $_POST["nome".$i];
		$descrizione = $_POST["descrizione".$i];
		$is_attivo = ($_POST['is_attivo'.$i]=="1")?1:0;
		$is_inhomepage = ($_POST['is_inhomepage'.$i]=="1")?1:0;
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
	
	for($i=1;$i<=$_SESSION["n_prodotti_composizione"];$i++){
		$nomeCampoForm = "codice".$i;
		if( isset($_POST[$nomeCampoForm]) && ($_POST[$nomeCampoForm]!="") ) {
			//Aggiungo questo prodotto 
			$codice_prodotto = $_POST[$nomeCampoForm];
			$insertSQL = sprintf("INSERT INTO %s (id_lookbook, codice_prodotto, ordinamento) VALUES (%s, %s, %s)",
			   $tabella."_prodotto",
			   GetSQLValueString($id_inserito, "int"),
			   GetSQLValueString($codice_prodotto, "text"),
			   GetSQLValueString($i, "int"));
			mysqli_select_db($std_conn, $database_std_conn);
			$Result1 = mysqli_query($std_conn, $insertSQL) or die(mysqli_error($std_conn));
			
		}
	}
	
	require_once("upload_redim_foto_lookbook_script.php");

	
	
	logThis($sufx_sezione, "Aggiunto", $id_inserito);
	//header("Location: ".$sufx_sezione."_gest.php?".$padre_get_post."=".$id_padre);
	//vado all'articolo in edit per la gestione magazzino.
	header("Location: ".$sufx_sezione."_edit.php?".$padre_get_post."=".$id_padre."&id=".$id_inserito."#magazzino");

}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"> 
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title><?php echo $_SESSION["www_title"]; ?> - Aggiungi composizione Lookbook</title>
		<?php require_once("header.php"); ?>
        <script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
	    <link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />

        
</head>
  
	<body><div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->
		
		<?php require_once("sidebar_messages.php"); ?>
		
		<div id="insert-content"> <!-- Main Content Section with everything -->
			
            <?php require_once("top_bar.php"); ?>

			<?php 
				require_once("breadcrump4.php");
				//passo l'id linea
				breadcrump($sufx_sezione,"add",$id_padre);
			?>

        	<form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="nomeform" value="vv" />
            
			<div class="content-box"><!-- Start Content Box -->
				
				<div class="content-box-header">
					
					<h3>Aggiunta Composizione per Lookbook</h3>
					
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
					?>
					<div class="tab-content <?php echo ($i == $_SESSION["linguadefault"])?'default-tab':''; ?>" id="tab<?=$i?>"> <!-- This is the target div. id must match the href of this div's tab -->
                    
						
							
							<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
							
							  <p>
								<label>Prodotto (<?=$row_rs_lingua["nome"]?>)</label>
								<input class="text-input medium-input" type="text" id="nome<?=$i?>" name="nome<?=$i?>" />
                                <br /><small>Es: Primavera / Estate 2011</small>
							  </p>		
                              
							  <p>
								<label>Descrizione (<?=$row_rs_lingua["nome"]?>)</label>
                                <textarea id="descrizione<?=$i?>" name="descrizione<?=$i?>" cols="40" rows="6"></textarea>                               
							  </p>		
                              
                            <p>
                                <label>Abilita in questa lingua</label>
                                
                                <input type="checkbox" name="is_attivo<?=$i?>" value="1" checked="checked" /> visualizza l'informazione in questa lingua.
                            </p>	 		                              
							
                           
								
							</fieldset>
							
							<div class="clear"></div><!-- End .clear -->
							
						
					</div> <!-- End #tabX -->
					<?php
					}while($row_rs_lingua = mysqli_fetch_assoc($rs_lingua));
					mysqli_data_seek($rs_lingua,0);
					$row_rs_lingua = mysqli_fetch_assoc($rs_lingua);
					?>					        
                    
				
				</div> <!-- End .content-box-content -->
			</div> <!-- End .content-box -->

			<div class="content-box column-left">
	            <div class="content-box-header">				
					<h3>E-commerce</h3>
				</div> <!-- End .content-box-header -->
				<div class="content-box-content">
                	
				<p>
                <label>Mostra online</label>
                    
                    <input type="checkbox" name="is_attivo" value="1" checked="checked" /> visualizza pubblicamente l'informazione sul web.
                </p>
                </div>	 		
            </div>
			<div class="content-box column-right">
	            <div class="content-box-header">				
					<h3>Area Riservata Negozi</h3>
				</div> <!-- End .content-box-header -->
				<div class="content-box-content">
                                		
				<p>
                <label>Mostra in area riservata dei negozi</label>
                    
                    <input type="checkbox" name="is_attivo_negozi" value="1" /> 
                    visualizza nell'area riservata dei negozi.
                </p>
                </div>	 		
            </div>
            <div class="clear"></div>

              <p>
                <label>Immagini Prodotto</label>     
                    <?php for($i=1;$i<=$_SESSION["n_img_prodotto"];$i++){ ?>
                        <input type="file" name="img<?php echo $i; ?>" /><small>Dimensioni Consigliate: 900 x 1200 - JPEG - fondo bianco</small><br />
                         
                    <?php } ?>         
                    
              </p>
              
              
			  <p>
                <label>Prodotti in questa composizione</label>     
                    <?php for($i=1;$i<=$_SESSION["n_prodotti_composizione"];$i++){ ?>
                        <input class="text-input small-input" type="text" id="codice<?php echo $i; ?>" name="codice<?php echo $i; ?>" /><small>Inserire il <strong>codice articolo</strong> del prodotto</small><br />
                         
                    <?php } ?>                             
              </p>
              
                
				<p>
                <input class="button" type="submit" value="Inserisci" onclick="javascript:tenta();" />&nbsp;&nbsp;<input class="button" type="button" value="Annulla" onclick="javascript:location.href='<?php echo $sufx_sezione; ?>_gest.php';" />

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
                
                
            </form>			

			
            <?php require_once("footer.php"); ?>

			
		</div> <!-- End #insert-content -->
		
	</div>
	</body>
  
</html>
<?php
mysqli_free_result($rs_lingua);
?>
