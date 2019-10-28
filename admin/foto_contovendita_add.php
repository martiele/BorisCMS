<?php 
require_once('../Connections/std_conn.php');
require_once('../funzioni.php');

$MM_authorizedUsers = "1,2,3";
require_once("restrict.php");

$sufx_sezione="foto_contovendita";
$tabella = "dny_foto_prodotto";
$sufx_sezione_padre="contovendita";
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
	
	$id_inserito = $id_padre;
	
	require_once("upload_redim_foto_script.php");

	
	logThis($sufx_sezione, "Aggiunto", $id_inserito);
	header("Location: ".$sufx_sezione."_gest.php?".$padre_get_post."=".$id_padre);
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"> 
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title><?php echo $_SESSION["www_title"]; ?> - Aggiungi Immagini al Prodotto</title>
		<?php require_once("header.php"); ?>
        <script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
	    <link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />

        
</head>
  
	<body><div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->
		
		<?php require_once("sidebar_messages.php"); ?>
		
		<div id="insert-content"> <!-- Main Content Section with everything -->
			
            <?php require_once("top_bar.php"); ?>

			<?php 
				require_once("breadcrump_cv.php");
				//passo l'id linea
				breadcrump($sufx_sezione,"add",$id_padre);
			?>

        	<form action="<?=$editFormAction?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="nomeform" value="vv" />
            <input type="hidden" name="<?=$padre_get_post?>" value="<?=$id_padre?>" />
          
			<div class="content-box"> <!-- Start Content Box -->
				
				<div class="content-box-header">
					
					<h3>Aggiungi Immagine</h3>
                    <ul class="content-box-tabs">
                        <li><a href="#tab1" class="default-tab">Aggiunta Immagine</a></li>
                    </ul>					
					
				</div>
                <div class="content-box-content">
                	<div class="tab-content default-tab" id="tab1"> <!-- This is the target div. id must match the href of this div's tab -->
                    
						
					  <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="nomeform" value="vv" />
            <input type="hidden" name="<?=$padre_get_post?>" value="<?=$id_padre?>" />
							
							<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
								
								
      							<p>
							    <label>Immagini Pacchetto</label>     
                                    <?php for($i=1;$i<=$_SESSION["n_img_prodotto"];$i++){ ?>
                                    	<input type="file" name="img<?php echo $i; ?>" /><small>Dimensioni Consigliate: 900 x 1200 - JPEG - fondo bianco</small><br />
                                         
                                    <?php } ?>         
									
								</p>

							
															
								
								<p>
									<input class="button" type="submit" value="Inserisci" />
								</p>
								
							</fieldset>
							
						</form>
						
					</div> <!-- End #tab1 -->
					
        
					
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->

			
            <?php require_once("footer.php"); ?>

			
		</div> <!-- End #insert-content -->
		
	</div>

	</body>
  
</html>
<?php
mysqli_free_result($rs_lingua);
?>
