<?php 
require_once('../Connections/std_conn.php');
require_once('../funzioni.php');

$MM_authorizedUsers = "1,2,3";
require_once("restrict.php");

$sufx_sezione="richiestadocumentazione";
$tabella = "dny_richiestadocumentazione";

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

if(isset($_GET["sblocca"]) && ($_GET["sblocca"]=="1") && ($_GET["idru"]!="")){
	$idru = (int)$_GET["idru"];
	if($idru>0){
		$insertSQL = sprintf("UPDATE `dny_richiestadocumentazione_utente` SET `completato`='0' WHERE `idru`=%s;",
			   GetSQLValueString($idru, "int"));
		mysqli_select_db($std_conn, $database_std_conn);
		$Result1 = mysqli_query($std_conn, $insertSQL) or die(mysqli_error($std_conn));
	}
	header("Location: ".$sufx_sezione."_info.php?id=".$colname_Recordset1);
	exit();
}
if(isset($_GET["sblocca"]) && ($_GET["sblocca"]=="-1") && ($_GET["idru"]!="")){
	$idru = (int)$_GET["idru"];
	if($idru>0){
		$insertSQL = sprintf("UPDATE `dny_richiestadocumentazione_utente` SET `completato`='1' WHERE `idru`=%s;",
			   GetSQLValueString($idru, "int"));
		mysqli_select_db($std_conn, $database_std_conn);
		$Result1 = mysqli_query($std_conn, $insertSQL) or die(mysqli_error($std_conn));
	}
	header("Location: ".$sufx_sezione."_info.php?id=".$colname_Recordset1);
	exit();	
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"> 
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo $_SESSION["www_title"]; ?> - Dettagli Richiesta di Documentazione</title>
		<?php require_once("header.php"); ?>
        
        <link rel="stylesheet" href="css/notifiche.css" type="text/css" media="screen" />


</head>
  
	<body><div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->
		
		<?php require_once("sidebar_messages.php"); ?>
		
		<div id="insert-content"> <!-- Main Content Section with everything -->
			
            <?php require_once("top_bar.php"); ?>

                
			<div class="content-box"><!-- Start Content Box -->
				
				<div class="content-box-header">
					
					<h3>Dettagli Richiesta di Documentazione</h3>
					
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
                    
								
							<p>
								<strong>Titolo:</strong> 
								<?=$row_rs_info["nome"]?>
							  </p>		

							  <p>
								<strong>Descrizione:</strong> 
                                <?=$row_rs_info["descrizione"]?>
							  </p>		
                            
	                      
							
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
	
    
    		
				
                <div class="divnotifiche">
                	<p>Lista utenti invitati ad inviare la <strong>Documentazione</strong></p>
                    
                	<div class="inattesa colonnanotifiche colonnanotifiche13r">
                    	<span class="titoletto">IN ATTESA DI RISPOSTA</span>
                        <p>Utenti notificati ma che non hanno ancora aperto la comunicazione</p>
<?php
mysqli_select_db($std_conn, $database_std_conn);
$query_Recordset5 = sprintf("SELECT * FROM dny_utente_newsletter LEFT JOIN dny_richiestadocumentazione_utente 
ON dny_utente_newsletter.id = dny_richiestadocumentazione_utente.id_utente_newsletter  AND id_richiestadocumentazione=%s
WHERE is_attivo=1 AND deleted=0 AND `accesso_completo`=1 AND `mostra_dati_sensibili`=0 AND idru is Null", 
					GetSQLValueString($colname_Recordset1, "int"));
$Recordset5 = mysqli_query($std_conn, $query_Recordset5) or die(mysqli_error($std_conn));
$row_Recordset5 = mysqli_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysqli_num_rows($Recordset5);
if($totalRows_Recordset5>0){
?>
        <ul>
            <?php do{ ?>
            <li><?php echo $row_Recordset5["nome"]; ?><?php if($row_Recordset5["azienda"]!=""){ echo " (".$row_Recordset5["azienda"].")"; } ?></li>
            <?php }while($row_Recordset5 = mysqli_fetch_assoc($Recordset5)); ?>
        </ul>
<?php }else{ ?>
	<br /><br /><br />Nessun nome in elenco
<?php 
} 
mysqli_free_result($Recordset5);
?>
                    </div>

                    
                    
                	<div class="interessati colonnanotifiche colonnanotifiche23">
                    	<span class="titoletto">Hanno <strong>RISPOSTO E CONFERMATO</strong> l'invio della documentazione</span>
<?php
mysqli_select_db($std_conn, $database_std_conn);
$query_Recordset5 = sprintf("SELECT * FROM dny_utente_newsletter LEFT JOIN dny_richiestadocumentazione_utente 
ON dny_utente_newsletter.id = dny_richiestadocumentazione_utente.id_utente_newsletter  AND id_richiestadocumentazione=%s
WHERE is_attivo=1 AND deleted=0 AND `accesso_completo`=1 AND `mostra_dati_sensibili`=0 AND idru is not Null AND completato=1 ORDER BY data_invio ASC, nome ASC", 
					GetSQLValueString($colname_Recordset1, "int"));
$Recordset5 = mysqli_query($std_conn, $query_Recordset5) or die(mysqli_error($std_conn));
$row_Recordset5 = mysqli_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysqli_num_rows($Recordset5);
if($totalRows_Recordset5>0){
?>
        <ul>
            <?php do{ ?>
            <li class="speciale"><strong class="grosso"><?php echo $row_Recordset5["nome"]; ?><?php if($row_Recordset5["azienda"]!=""){ echo " (".$row_Recordset5["azienda"].")"; } ?></strong> 
            	<a href="<?php echo $sufx_sezione; ?>_info.php?id=<?php echo $row_Recordset1['id']; ?>&sblocca=1&idru=<?php echo $row_Recordset5["idru"]; ?>">sblocca utente per tornare in modifica/upload</a><br />
            	<strong>Data invio:</strong> <?php echo data_a_video($row_Recordset5["data_invio"],true); ?><br />
            	<strong>Messaggio / Note:</strong> <?php echo $row_Recordset5["note"]; ?>


				<?php
				mysqli_select_db($std_conn, $database_std_conn);
				$tipo_allegato = "docu_utente";
				$articolocorrente = $colname_Recordset1;
				$idutentecorrente = $row_Recordset5["id"];
				$idrucorrente =  $row_Recordset5["idru"];
				$dirfile = "../public/richiestedocumentazione/ric_".$articolocorrente."/user_".$idutentecorrente."/";
				$query_Allegati = sprintf("SELECT * FROM dny_allegati WHERE tabella=%s AND id_ref=%s", 
									GetSQLValueString($tipo_allegato, "text"),
									GetSQLValueString($idrucorrente, "int"));
				$RSA = mysqli_query($std_conn, $query_Allegati) or die(mysqli_error($std_conn));
				$row_RSA = mysqli_fetch_assoc($RSA);
				$totalRows_RSA = mysqli_num_rows($RSA);
				if($totalRows_Recordset1>0){	
				?>
                <br />
            	<strong>Files e Documenti Allegati:</strong>
                <ul>
                <?php
					do{
						$nomefile = $row_RSA["nomefile"];
						$filecompleto = $dirfile . $nomefile;
						if(($nomefile!="")){
				?>
                   	<li><a href="<?php echo $filecompleto; ?>" target="_blank"><img src="resources/images/icons/download_file.png" width="40" /> <?php echo substr($nomefile,strpos($nomefile,"_")+1); ?></a></li>

                <?php
						}
					}while($row_RSA = mysqli_fetch_assoc($RSA));	
				?>
                </ul>
                <?php				
				}
				mysqli_free_result($RSA);				
				?> 

            
            </li>
            <?php }while($row_Recordset5 = mysqli_fetch_assoc($Recordset5)); ?>
        </ul>
<?php }else{ ?>
	<br /><br /><br />Nessun nome in elenco
<?php 
} 
mysqli_free_result($Recordset5);
?>
                    </div>


                	<div class="interessati2 colonnanotifiche colonnanotifiche23">
                    	<span class="titoletto">Hanno <strong>LETTO E/O INIZIATO</strong> AD INVIARE I DATI <small>(ma non hanno ancora confermato l'invio come definitivo)</small></span>
<?php
mysqli_select_db($std_conn, $database_std_conn);
$query_Recordset5 = sprintf("SELECT * FROM dny_utente_newsletter LEFT JOIN dny_richiestadocumentazione_utente 
ON dny_utente_newsletter.id = dny_richiestadocumentazione_utente.id_utente_newsletter  AND id_richiestadocumentazione=%s
WHERE is_attivo=1 AND deleted=0 AND `accesso_completo`=1 AND `mostra_dati_sensibili`=0 AND idru is not Null AND completato=0 ORDER BY data_invio ASC, nome ASC", 
					GetSQLValueString($colname_Recordset1, "int"));
$Recordset5 = mysqli_query($std_conn, $query_Recordset5) or die(mysqli_error($std_conn));
$row_Recordset5 = mysqli_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysqli_num_rows($Recordset5);
if($totalRows_Recordset5>0){
?>
        <ul>
            <?php do{ ?>
            <li class="speciale"><strong class="grosso"><?php echo $row_Recordset5["nome"]; ?><?php if($row_Recordset5["azienda"]!=""){ echo " (".$row_Recordset5["azienda"].")"; } ?></strong>
            	<a href="<?php echo $sufx_sezione; ?>_info.php?id=<?php echo $row_Recordset1['id']; ?>&sblocca=-1&idru=<?php echo $row_Recordset5["idru"]; ?>">blocca utente per segnare come completato</a><br />
            	<?php if($row_Recordset5["data_invio"]!=""){ ?>
                    <strong>Data invio:</strong> <?php echo data_a_video($row_Recordset5["data_invio"],true); ?><br />
                    <strong>Messaggio / Note:</strong> <?php echo $row_Recordset5["note"]; ?>
    
    
                    <?php
                    mysqli_select_db($std_conn, $database_std_conn);
                    $tipo_allegato = "docu_utente";
                    $articolocorrente = $colname_Recordset1;
                    $idutentecorrente = $row_Recordset5["id"];
                    $idrucorrente =  $row_Recordset5["idru"];
                    $dirfile = "../public/richiestedocumentazione/ric_".$articolocorrente."/user_".$idutentecorrente."/";
                    $query_Allegati = sprintf("SELECT * FROM dny_allegati WHERE tabella=%s AND id_ref=%s", 
                                        GetSQLValueString($tipo_allegato, "text"),
                                        GetSQLValueString($idrucorrente, "int"));
                    $RSA = mysqli_query($std_conn, $query_Allegati) or die(mysqli_error($std_conn));
                    $row_RSA = mysqli_fetch_assoc($RSA);
                    $totalRows_RSA = mysqli_num_rows($RSA);
                    if($totalRows_Recordset1>0){	
                    ?>
                    <br />
                    <strong>Files e Documenti Allegati:</strong>
                    <ul>
                    <?php
                        do{
                            $nomefile = $row_RSA["nomefile"];
                            $filecompleto = $dirfile . $nomefile;
                            if(($nomefile!="")){
                    ?>
                        <li><a href="<?php echo $filecompleto; ?>" target="_blank"><img src="resources/images/icons/download_file.png" width="40" /> <?php echo substr($nomefile,strpos($nomefile,"_")+1); ?></a></li>
    
                    <?php
                            }
                        }while($row_RSA = mysqli_fetch_assoc($RSA));	
                    ?>
                    </ul>
                    <?php				
                    }
                    mysqli_free_result($RSA);				
                    ?> 
				<?php
				}else{ echo "<small>ha solo aperto il messaggio, non è presente un invio parziale dei dati</small>"; }
				?>            
            </li>
            <?php }while($row_Recordset5 = mysqli_fetch_assoc($Recordset5)); ?>
        </ul>
<?php }else{ ?>
	<br /><br /><br />Nessun nome in elenco
<?php 
} 
mysqli_free_result($Recordset5);
?>
                    </div>
                    

                	<div class="clear_invisibile">&nbsp;</div>
                </div>

            
            <p>
                <input class="button" type="button" value="Chiudi" onclick="javascript:location.href='<?php echo $sufx_sezione; ?>_gest.php';" />
            </p>
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
