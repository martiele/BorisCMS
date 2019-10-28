<?php 
require_once('../Connections/std_conn.php');
require_once('../funzioni.php');

$MM_authorizedUsers = "1,2,3";
require_once("restrict.php");

//serve per i redirect e i link. "viaggi_gest.php"
$sufx_sezione="lingue";
$tabella = "dny_lingua";

// ELIMINO
if( (isset($_GET["elimina"])) && ($_GET["elimina"]=="1") ){
	//NON elimino, segno come "NON NOVITA'"
	if( (isset($_GET["id"])) && ($_GET["id"]>0) ){
		$deleteSQL = sprintf("UPDATE dny_prodotto SET is_novita=0 WHERE id=%s LIMIT 1",
		   GetSQLValueString($_GET["id"], "int"));
		mysqli_select_db($std_conn, $database_std_conn);
		mysqli_query($std_conn, $deleteSQL) or die(mysqli_error($std_conn));
	}
}

mysqli_select_db($std_conn, $database_std_conn);
$query_Recordset1 = sprintf("SELECT * FROM %s WHERE deleted = 0 ORDER BY ordinamento ASC", $tabella);
$Recordset1 = mysqli_query($std_conn, $query_Recordset1) or die(mysqli_error($std_conn));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

$queryString_Recordset1 = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset1") == false && 
        stristr($param, "totalRows_Recordset1") == false &&
        stristr($param, "elimina") == false &&
        stristr($param, "id") == false) 
	{
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset1 = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset1 = sprintf("&totalRows_Recordset1=%d%s", $totalRows_Recordset1, $queryString_Recordset1);


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
		<title><?php echo $_SESSION["www_title"]; ?> - Prodotti Novità</title>
		<?php require_once("header.php"); ?>
	</head>
  
	<body><div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->
		
		<?php require_once("sidebar_messages.php"); ?>
		
		<div id="main-content"> <!-- Main Content Section with everything -->
			
            <?php require_once("top_bar.php"); ?>

			
			<div class="content-box"><!-- Start Content Box -->
				
				
				<div class="content-box-content">
					
					<div class="tab-content default-tab"> <!-- This is the target div. id must match the href of this div's tab -->
						<!--
						<div class="notification attention png_bg">
							<a href="#" class="close"><img src="resources/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
							<div>
								This is a Content Box. You can put whatever you want in it. By the way, you can close this notification with the top-right cross.
							</div>
						</div>
						-->
						<div class="notification attention png_bg">
							<a href="#" class="close"><img src="resources/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
							<div>
								Tramite la tabella sottostante puoi vedere e modificare i prodotti segnalati come novità.</div>
						</div>
						                      
						
<?php if ($totalRows_Recordset1 > 0) { // Show if recordset not empty ?>

        <div class="content-box"><!-- Start Content Box -->
            
            <div class="content-box-header">
                
                <h3>Elenco Prodotti Novità (per lingua)</h3>
                
                <ul class="content-box-tabs">
                    <?php
                    do{ 
                        $i = $row_Recordset1["id"];
                    ?>
                    <li><a href="#tab<?=$i?>" <?php echo ($i == $_SESSION["linguadefault"])?'class="default-tab"':''; ?>><?=$row_Recordset1["nome"]?></a></li>
                    <?php
                    } while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1));
                    mysqli_data_seek($Recordset1,0);
                    $row_Recordset1 = mysqli_fetch_assoc($Recordset1);
                    ?>
                </ul>
                
                <div class="clear"></div>
                
            </div> <!-- End .content-box-header -->
				
				<div class="content-box-content">


					
					<?php
					do{ 
						$i = $row_Recordset1["id"];    
?>
					<div class="tab-content <?php echo ($i == $_SESSION["linguadefault"])?'default-tab':''; ?>" id="tab<?=$i?>"> <!-- This is the target div. id must match the href of this div's tab -->

<?php
	$valore_testuale = date("d/m/Y");
	$date = explode("/",$valore_testuale);
	$data_fine = mktime(0,0,0,$date[1],$date[0]-14,$date[2]);
	$valore_testuale = date("d/m/Y",$data_fine);
	$query_rs = sprintf("SELECT dny_prodotto.*, dny_prodotto_lingua.nome FROM dny_prodotto JOIN dny_prodotto_lingua ON dny_prodotto.id = dny_prodotto_lingua.id_ref WHERE dny_prodotto_lingua.id_lingua=%s AND dny_prodotto_lingua.is_attivo=1 AND dny_prodotto.is_attivo=1 AND dny_prodotto.deleted=0 AND is_novita=1 AND modified>=%s ORDER BY modified DESC", 
	   GetSQLValueString($i,"int"),
	   GetSQLValueString(data_a_database($valore_testuale),"date"));
$rs = mysqli_query($std_conn, $query_rs) or die(mysqli_error($std_conn));
$row_rs = mysqli_fetch_assoc($rs);
$totalRows_rs = mysqli_num_rows($rs);
if($totalRows_rs>0){
?>
<table>
<?php
	do{
		$colname_rs_fotoprodotto = $row_rs['id'];
		mysqli_select_db($std_conn, $database_std_conn);
		$query_rs_fotoprodotto = sprintf("SELECT * FROM dny_foto_prodotto WHERE id_prodotto = %s AND deleted=0 ORDER BY ordinamento ASC", GetSQLValueString($colname_rs_fotoprodotto, "int"));
		$rs_fotoprodotto = mysqli_query($std_conn, $query_rs_fotoprodotto) or die(mysqli_error($std_conn));
		$row_rs_fotoprodotto = mysqli_fetch_assoc($rs_fotoprodotto);
		$totalRows_rs_fotoprodotto = mysqli_num_rows($rs_fotoprodotto);
		$imgsrc="";
		if($totalRows_rs_fotoprodotto){
			$nomefile = $row_rs_fotoprodotto["nomefile"];
			$filesmall = $_SESSION["path_upload_admin"].$_SESSION["path_foto_prodotto"].$_SESSION["path_fotosmall_prodotto"].$nomefile;
			if(($nomefile!="")&&(file_exists($filesmall))){
				$imgsrc = sprintf('<a href="prodotto.php?id=%s"><img src="%s" border="0" height="80" /></a>',$colname_rs_fotoprodotto,$filesmall);
			}else{
				$imgsrc = sprintf('<a href="prodotto.php?id=%s"><img src="%s" border="0" height="80" /></a>',$colname_rs_fotoprodotto,"images/noimage_small.jpg");
			}
		}
		mysqli_free_result($rs_fotoprodotto);
		
		//Taglie e Colori (almeno una variante)
		$colname_rs_colorprodotto = $row_rs['id'];
		mysqli_select_db($std_conn, $database_std_conn);
		$query_rs_colorprodotto = sprintf("SELECT * FROM dny_taglia_colore WHERE id_prodotto = %s AND deleted=0 ORDER BY ordinamento ASC", GetSQLValueString($colname_rs_colorprodotto, "int"));
		$rs_colorprodotto = mysqli_query($std_conn, $query_rs_colorprodotto) or die(mysqli_error($std_conn));
		if(!($row_rs_colorprodotto = mysqli_fetch_assoc($rs_colorprodotto))){
			mysqli_free_result($rs_colorprodotto);
			continue;
		}
		mysqli_free_result($rs_colorprodotto);
?>
<tr>
	<td><?=$imgsrc?></td>
    <td><?=$row_rs["nome"]?></td>
    <td>&euro; <?=number_format($row_rs["prezzo"],2,",","")?></td>
    <td>
      <!-- Icons -->
      <a href="prodotti_edit.php?id=<?=$row_rs["id"]?>&sottocategoria=<?=$row_rs["id_sottocategoria"]?>" title="Edit" target="_blank"><img src="resources/images/icons/pencil.png" alt="Edit" /></a>
      <a href="novita_gest.php?elimina=1&id=<?php echo $row_rs['id']; ?>&lang=<?=$i?>" title="Delete" onclick="return confirm('Rimuovere dalle novit&agrave;?');"><img src="resources/images/icons/cross.png" alt="Delete" /></a> 
    </td>
</tr>
<?php
	}while($row_rs = mysqli_fetch_assoc($rs));
?>
</table>
<?php
}
?>                    
							<div class="clear"></div><!-- End .clear -->
												
					</div> <!-- End #tabX -->
					<?php
                    } while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1));
                    mysqli_data_seek($Recordset1,0);
                    $row_Recordset1 = mysqli_fetch_assoc($Recordset1);
					?>		
					
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->

<?php } ?>


<?php if ($totalRows_Recordset1 == 0) { // Show if recordset empty ?>
  <table>
      <tr>
        <td>&nbsp;</td>
        <td>Nessuna lingua in elenco</td>
        <td>&nbsp;</td>
      </tr>
  </table>
  <?php } // Show if recordset empty ?>
                       
						
					</div> <!-- End #tab1 -->
					
        
					
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->
			

			
            <?php require_once("footer.php"); ?>

			
		</div> <!-- End #main-content -->
		
	</div></body>
  
</html>
<?php
mysqli_free_result($Recordset1);
?>
