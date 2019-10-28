<?php 
require_once('../Connections/std_conn.php');
require_once('../funzioni.php');

$MM_authorizedUsers = "1,2,3";
require_once("restrict.php");

$sufx_sezione="categorie";
$tabella = "dny_categoria";
$sufx_sezione_padre="linee";
$tabella_padre = "dny_linea";
$label_id_padre = "id_linea";
$padre_get_post = "linea";
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
	
	//Recupero il prossimo num ordinamento
	mysqli_select_db($std_conn, $database_std_conn);
	$query_nextOrdinamento = sprintf("SELECT MAX(ordinamento) massimo FROM %s WHERE %s=%s",
		$tabella,
		$label_id_padre,
		GetSQLValueString($id_padre, "int"));
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
	$mostra_dati_sensibili = ($_POST['mostra_dati_sensibili']=="1")?1:0;

	$insertSQL = sprintf("INSERT INTO %s (%s, ordinamento, is_attivo, mostra_dati_sensibili) VALUES (%s, %s, %s, %s)",
			   $tabella,
			   $label_id_padre,
			   GetSQLValueString($id_padre, "int"),
			   GetSQLValueString($ordinamento, "int"),
			   GetSQLValueString($is_attivo, "int"),
			   GetSQLValueString($mostra_dati_sensibili, "int"));
	mysqli_select_db($std_conn, $database_std_conn);
	$Result1 = mysqli_query($std_conn, $insertSQL) or die(mysqli_error($std_conn));
	$id_inserito = mysqli_insert_id($std_conn);
	
	do{ 
		$i = $row_rs_lingua["id"];
		$nome = $_POST["nome".$i];
		$is_attivo = ($_POST['is_attivo'.$i]=="1")?1:0;
		if($nome!=""){
			$insertSQL = sprintf("INSERT INTO %s (id_ref, id_lingua, nome, is_attivo) VALUES (%s, %s, %s, %s)",
			   $tabella."_lingua",
			   GetSQLValueString($id_inserito, "int"),
			   GetSQLValueString($i, "int"),
			   GetSQLValueString($nome, "text"),
			   GetSQLValueString($is_attivo, "int"));
			mysqli_select_db($std_conn, $database_std_conn);
			$Result1 = mysqli_query($std_conn, $insertSQL) or die(mysqli_error($std_conn));
			
		}	
	}while($row_rs_lingua = mysqli_fetch_assoc($rs_lingua));
	mysqli_data_seek($rs_lingua,0);
	$row_rs_lingua = mysqli_fetch_assoc($rs_lingua);	
	
	logThis($sufx_sezione, "Aggiunto", $id_inserito);
	header("Location: ".$sufx_sezione."_gest.php?".$padre_get_post."=".$id_padre);

}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"> 
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo $_SESSION["www_title"]; ?> - Aggiungi Categoria</title>
		<?php require_once("header.php"); ?>
        <script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
	    <link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />

        
</head>
  
	<body><div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->
		
		<?php require_once("sidebar_messages.php"); ?>
		
		<div id="insert-content"> <!-- Main Content Section with everything -->
			
            <?php require_once("top_bar.php"); ?>

			<?php 
				require_once("breadcrump.php");
				//passo l'id linea
				breadcrump($sufx_sezione,"add",$id_padre);
			?>

        	<form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="nomeform" value="vv" />
            <input type="hidden" name="<?=$padre_get_post?>" value="<?=$id_padre?>" />
            
			<div class="content-box"><!-- Start Content Box -->
				
				<div class="content-box-header">
					
					<h3>Aggiunta Linea</h3>
					
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
								<label>Categoria (<?=$row_rs_lingua["nome"]?>)</label>
								<input class="text-input medium-input" type="text" id="nome<?=$i?>" name="nome<?=$i?>" />
                                <br /><small>Es: Primavera / Estate 2011</small>
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
	
                <p>
                    <label>Contiene dati sensibili?</label>
                    
                    <input type="checkbox" name="mostra_dati_sensibili" value="1" /> categoria riservata ai soli utenti abilitati alla visualizzazione dei dati sensibili.
                </p>	 		
				<p>
                    <label>Mostra online</label>
                    
                    <input type="checkbox" name="is_attivo" value="1" /> visualizza pubblicamente l'informazione sul web.
                </p>	 		
                
                <p>
                    <input class="button" type="submit" value="Inserisci" />
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
