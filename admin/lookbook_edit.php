<?php 
require_once('../Connections/std_conn.php');
require_once('../funzioni.php');

$MM_authorizedUsers = "1,2,3";
require_once("restrict.php");

$sufx_sezione="lookbook";
$tabella = "dny_lookbook";


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
	$is_attivo_negozi = ($_POST['is_attivo_negozi']=="1")?1:0;
	$is_novita = ($_POST['is_novita']=="1")?1:0;
	$insertSQL = sprintf("UPDATE %s SET 
						 is_attivo=%s,
						 is_attivo_negozi=%s,
						 modified=%s
						 WHERE id=%s",
			   $tabella,
			   GetSQLValueString($is_attivo, "int"),
			   GetSQLValueString($is_attivo_negozi, "int"),
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


	//Rimuovo le associazioni (prodotti) precedenti e le reinserisco
	$insertSQL = sprintf("DELETE FROM %s WHERE id_lookbook=%s",
	   $tabella."_prodotto",
	   GetSQLValueString($id_inserito, "int"));
	mysqli_select_db($std_conn, $database_std_conn);
	$Result1 = mysqli_query($std_conn, $insertSQL) or die(mysqli_error($std_conn));	
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


logThis($sufx_sezione, "Modificato", $id_inserito);
header("Location: ".$sufx_sezione."_gest.php?".$padre_get_post."=".$id_padre);

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"> 
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title><?php echo $_SESSION["www_title"]; ?> - Modifica composizione Lookbook</title>
		<?php require_once("header.php"); ?>

        <script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
	    <link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
        
		<script type="text/javascript" src="./fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
        <script type="text/javascript" src="./fancybox/jquery.fancybox-1.3.4.pack.js"></script>
        <link rel="stylesheet" type="text/css" href="./fancybox/jquery.fancybox-1.3.4.css" media="screen" />
		<script type="text/javascript">
            $(document).ready(function() {
                $(".myFancy").fancybox({
                    'transitionIn'	: 'elastic',
                    'transitionOut'	: 'elastic'
                });
            });
        </script>        


</head>
  
	<body><div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->
		
		<?php require_once("sidebar_messages.php"); ?>
		
		<div id="insert-content"> <!-- Main Content Section with everything -->
			
            <?php require_once("top_bar.php"); ?>
            
			<?php 
				require_once("breadcrump4.php");
				//passo l'id categoria
				breadcrump($sufx_sezione,"edit",$id_padre);
			?>

	        <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="nomeform" value="vv" />
                <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>" />
				<input type="hidden" name="<?=$padre_get_post?>" value="<?=$id_padre?>" />                
			<div class="content-box"><!-- Start Content Box -->
				
				<div class="content-box-header">
					
					<h3>Modifica composizione Lookbook</h3>
					
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
								<label>Prodotto (<?=$row_rs_lingua["nome"]?>)</label>
								<input class="text-input medium-input" type="text" id="nome<?=$i?>" name="nome<?=$i?>" value="<?=$row_rs_info["nome"]?>" />
                                <br /><small>Es: Primavera / Estate 2011</small>
							  </p>		

							  <p>
								<label>Descrizione (<?=$row_rs_lingua["nome"]?>)</label>
                                <textarea id="descrizione<?=$i?>" name="descrizione<?=$i?>" cols="40" rows="6"><?=$row_rs_info["descrizione"]?></textarea>                               
							  </p>		
                              
                            <p>
                                <label>Abilita in questa lingua</label>
                                
                                <input type="checkbox" name="is_attivo<?=$i?>" value="1" <?php if (!(strcmp($row_rs_info['is_attivo'],"1"))) {echo "checked=\"checked\"";} ?> /> visualizza l'informazione in questa lingua.
                            </p>
                            
	                       
    
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


			<div class="content-box column-left">
	            <div class="content-box-header">				
					<h3>E-commerce</h3>
				</div> <!-- End .content-box-header -->
				<div class="content-box-content">
             
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
                
                		
				<p>                
                <label>Mostra in area riservata dei negozi</label>
                    
                    <input type="checkbox" name="is_attivo_negozi" value="1" <?php if (!(strcmp($row_Recordset1['is_attivo_negozi'],"1"))) {echo "checked=\"checked\"";} ?> /> 
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
<?php
$colname_rs_fotoprodotto = $_GET['id'];
mysqli_select_db($std_conn, $database_std_conn);
$query_rs_fotoprodotto = sprintf("SELECT dny_lookbook_prodotto.*, dny_prodotto.id, dny_prodotto.deleted prodottoeliminato FROM dny_lookbook_prodotto LEFT JOIN dny_prodotto ON dny_lookbook_prodotto.codice_prodotto = dny_prodotto.codice WHERE id_lookbook=%s AND dny_lookbook_prodotto.deleted=0 ORDER BY dny_lookbook_prodotto.ordinamento ASC",
		GetSQLValueString($colname_rs_fotoprodotto, "int"));
$rs_fotoprodotto = mysqli_query($std_conn, $query_rs_fotoprodotto) or die(mysqli_error($std_conn));
$row_rs_fotoprodotto = mysqli_fetch_assoc($rs_fotoprodotto);
$totalRows_rs_fotoprodotto = mysqli_num_rows($rs_fotoprodotto);
if($totalRows_rs_fotoprodotto){
	$i = 1;
	do{
		if( ($row_rs_fotoprodotto["id"]>0) && ($row_rs_fotoprodotto["prodottoeliminato"]=="0") ){
			$trovato = "<span style='color:#060; font-weight:bold;'>PRODOTTO ATTIVO</span>";
		}else{
			$trovato = "<span style='color:#F00; font-weight:bold;'>NON TROVATO</span>";			
		}
?>
		<input class="text-input small-input" type="text" id="codice<?php echo $i; ?>" name="codice<?php echo $i; ?>" value="<?php echo $row_rs_fotoprodotto["codice_prodotto"]; ?>" /> <?php echo $trovato; ?> | <small>modificare il <strong>codice articolo</strong> del prodotto</small><br />
<?php		
		$i++;
	}while($row_rs_fotoprodotto = mysqli_fetch_assoc($rs_fotoprodotto));
}
mysqli_free_result($rs_fotoprodotto);
?>
                    <?php for(;$i<=$_SESSION["n_prodotti_composizione"];$i++){ ?>
                        <input class="text-input small-input" type="text" id="codice<?php echo $i; ?>" name="codice<?php echo $i; ?>" /><small>Inserire il <strong>codice articolo</strong> del prodotto</small><br />
                         
                    <?php } ?>                             
              </p>



						

            <p>
                <input class="button" type="submit" value="Aggiorna" onclick="javascript:tenta();" />&nbsp;&nbsp;<input class="button" type="button" value="Chiudi" onclick="javascript:location.href='<?php echo $sufx_sezione; ?>_gest.php';" />

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

	
                          
<?php
$colname_rs_fotoprodotto = $_GET['id'];
mysqli_select_db($std_conn, $database_std_conn);
$query_rs_fotoprodotto = sprintf("SELECT * FROM dny_foto_lookbook WHERE id_prodotto = %s AND deleted=0 ORDER BY ordinamento ASC", GetSQLValueString($colname_rs_fotoprodotto, "int"));
$rs_fotoprodotto = mysqli_query($std_conn, $query_rs_fotoprodotto) or die(mysqli_error($std_conn));
$row_rs_fotoprodotto = mysqli_fetch_assoc($rs_fotoprodotto);
$totalRows_rs_fotoprodotto = mysqli_num_rows($rs_fotoprodotto);
if($totalRows_rs_fotoprodotto){
?>
<p><b>Per eliminare e riordinare le foto del prodotto, <a href="foto_lookbook_gest.php?prodotto=<?=$colname_rs_fotoprodotto?>">clicca qui</a></b><br />

<?php
	do{
		$nomefile = $row_rs_fotoprodotto["nomefile"];
		$filebig = $_SESSION["path_upload_admin"].$_SESSION["path_foto_lookbook"].$_SESSION["path_fotobig_prodotto"].$nomefile;
		$filesmall = $_SESSION["path_upload_admin"].$_SESSION["path_foto_lookbook"].$_SESSION["path_fotosmall_prodotto"].$nomefile;
		if(($nomefile!="")&&(file_exists($filesmall))){
?>
			<a href="<?php echo $filebig; ?>" target="_blank" class="myFancy"><img src="<?php echo $filesmall; ?>" border="0" style="margin:10px;" height="80" /></a>
<?php
		}
	}while($row_rs_fotoprodotto = mysqli_fetch_assoc($rs_fotoprodotto));
?>
</p>
<?php
}
mysqli_free_result($rs_fotoprodotto);
?>    
			
            </form>
            
                  
            
            
            
            <?php require_once("footer.php"); ?>

			
		</div> <!-- End #insert-content -->
		
	</div>
   
    </body>
    
</html>
<?php
mysqli_free_result($Recordset1);
mysqli_free_result($rs_lingua);
?>
