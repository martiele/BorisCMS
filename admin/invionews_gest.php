<?php 
require_once('../Connections/std_conn.php');
require_once('../funzioni.php');

$MM_authorizedUsers = "1,2";
require_once("restrict.php");

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"> 
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title><?php echo $_SESSION["www_title"]; ?> - Invio notifiche ad utenti</title>
		<?php require_once("header.php"); ?>
    <script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
        <script src="../SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
        <script src="../SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
        <link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />


    <link href="../SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
    <link href="../SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
    
</head>
  
	<body><div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->
		
		<?php require_once("sidebar_messages.php"); ?>
		
		<div id="insert-content"> <!-- Main Content Section with everything -->
			
            <?php require_once("top_bar.php"); ?>

			
			<div class="content-box"><!-- Start Content Box -->
				
				<div class="content-box-header">
					
					<h3>Invio Notifiche agli utenti</h3>
					
					<ul class="content-box-tabs">
						<li><a href="#tab1" class="default-tab">Invio Notifiche</a></li>
					</ul>
					
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
					
					<div class="tab-content default-tab" id="tab1"> <!-- This is the target div. id must match the href of this div's tab -->
                    
						
					  <form action="invionews_complete.php" method="post" enctype="multipart/form-data">
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
    <label>Seleziona le categorie per cui inviare le notifiche:</label>
    <?php do{ 
	
mysqli_select_db($std_conn, $database_std_conn);
$query_RecordsetArtCat = sprintf("SELECT dny_news.* FROM (dny_news JOIN dny_sottocategoria ON dny_news.id_sottocategoria = dny_sottocategoria.id) WHERE dny_sottocategoria.id_categoria=%s AND dny_sottocategoria.is_attivo=1 AND dny_sottocategoria.deleted=0 AND dny_news.is_attivo=1 AND dny_news.deleted=0 AND dny_news.segnalato_newsletter=0", 
					GetSQLValueString($row_RecordsetCat["id"],"int"));
$RecordsetArtCat = mysqli_query($std_conn, $query_RecordsetArtCat) or die(mysqli_error($std_conn));
$row_RecordsetArtCat = mysqli_fetch_assoc($RecordsetArtCat);
$totalRows_RecordsetArtCat = mysqli_num_rows($RecordsetArtCat);
if($totalRows_RecordsetArtCat>0){
	$selezionabile=true;
}else{
	$selezionabile=false;	
}
mysqli_free_result($RecordsetArtCat);

	
	?>
    	<input type="checkbox" name="cat_<?=$row_RecordsetCat["id"]?>" value="1" <?php if(!$selezionabile){ echo 'disabled="disabled"'; } ?> /> <?=$row_RecordsetCat["nome"]?> (nuovi art.: <?=$totalRows_RecordsetArtCat?>)<br />
    <?php }while($row_RecordsetCat = mysqli_fetch_assoc($RecordsetCat)); ?>
    <small>L'utente verr&agrave; avvisato della pubblicazione di nuovi articoli nelle categorie selezionate.</small>
    </p>
<?php
}
mysqli_free_result($RecordsetCat);
?>
						 
                                                       
								
								<p>
									<input class="button" type="submit" value="Invia Email" />
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
    
	</body>
  
</html>
