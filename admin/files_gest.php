<?php 
require_once('../Connections/std_conn.php');
require_once('../funzioni.php');

$MM_authorizedUsers = "1,2,3";
require_once("restrict.php");

//serve per i redirect e i link. "viaggi_gest.php"
$sufx_sezione="files";
$tabella = "dny_file_generati";


	mysqli_select_db($std_conn, $database_std_conn);
	$query_sezioni = "SELECT * FROM dny_modelli WHERE eliminato=0 ORDER BY ORDINAMENTO ASC";
	$var_sezioni = mysqli_query($std_conn, $query_sezioni) or die(mysqli_error($std_conn));
	while($array_sezioni = mysqli_fetch_assoc($var_sezioni)){
		$arr_sezioni[$array_sezioni["ids"]] = $array_sezioni["nome"]; 
	}



// ELIMINO
if( (isset($_GET["elimina"])) && ($_GET["elimina"]=="1") ){
	if( (isset($_GET["idc"])) && ($_GET["idc"]>0) ){
		$deleteSQL = sprintf("UPDATE %s SET eliminato=1 WHERE 		
							idc=%s LIMIT 1",$tabella,
		   GetSQLValueString($_GET["idc"], "int"));
		mysqli_select_db($std_conn, $database_std_conn);
		mysqli_query($std_conn, $deleteSQL) or die(mysqli_error($std_conn));
	}
}

// MODIFICA ORDINAMENTO
if( (isset($_POST["riordina"])) && ($_POST["riordina"]=="1") ){
	mysqli_select_db($std_conn, $database_std_conn);
	$query_rs_aggiorna_ordine = sprintf("SELECT * FROM %s WHERE eliminato=0 ORDER BY ordinamento ASC", $tabella);
	$rs_aggiorna_ordine = mysqli_query($std_conn, $query_rs_aggiorna_ordine) or die(mysqli_error($std_conn));
	$row_rs_aggiorna_ordine = mysqli_fetch_assoc($rs_aggiorna_ordine);
	$totalRows_rs_aggiorna_ordine = mysqli_num_rows($rs_aggiorna_ordine);
	if($totalRows_rs_aggiorna_ordine>0){
		do{
			if(isset($_POST["ordinamento".$row_rs_aggiorna_ordine["idc"]])){
				$valore = $_POST["ordinamento".$row_rs_aggiorna_ordine["idc"]];
				$valore = str_replace(",",".",$valore);
				$updateSQL = sprintf("UPDATE %s SET ordinamento=%s WHERE idc=%s LIMIT 1", $tabella,
				   GetSQLValueString($valore, "float"),
				   GetSQLValueString($row_rs_aggiorna_ordine["idc"], "int"));
				mysqli_select_db($std_conn, $database_std_conn);
				mysqli_query($std_conn, $updateSQL) or die(mysqli_error($std_conn));
			}
		}while($row_rs_aggiorna_ordine = mysqli_fetch_assoc($rs_aggiorna_ordine));
	}
	mysqli_free_result($rs_aggiorna_ordine);
}

$maxRows_Recordset1 = 20;
$pageNum_Recordset1 = 0;
if (isset($_GET['pageNum_Recordset1'])) {
  $pageNum_Recordset1 = $_GET['pageNum_Recordset1'];
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;

mysqli_select_db($std_conn, $database_std_conn); //qui
$query_Recordset1 = sprintf("SELECT A.*, B.fileimg as filemodello
	FROM %s A LEFT JOIN dny_modelli B ON A.id_sezione = B.ids 
	WHERE A.eliminato=0 
	ORDER BY A.ordinamento ASC", 
$tabella);

$query_limit_Recordset1 = sprintf("%s LIMIT %d, %d", $query_Recordset1, $startRow_Recordset1, $maxRows_Recordset1);
$Recordset1 = mysqli_query($std_conn, $query_limit_Recordset1) or die(mysqli_error($std_conn));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);

if (isset($_GET['totalRows_Recordset1'])) {
  $totalRows_Recordset1 = $_GET['totalRows_Recordset1'];
} else {
  $all_Recordset1 = mysqli_query($std_conn, $query_Recordset1);
  $totalRows_Recordset1 = mysqli_num_rows($all_Recordset1);
}
$totalPages_Recordset1 = ceil($totalRows_Recordset1/$maxRows_Recordset1)-1;

$currentPage = $_SERVER["PHP_SELF"];

$queryString_Recordset1 = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset1") == false && 
        stristr($param, "totalRows_Recordset1") == false &&
        stristr($param, "elimina") == false &&
        stristr($param, "idc") == false) 
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
		<title><?php echo $_SESSION["www_title"]; ?> - Elenco Notizie</title>
		<?php require_once("header.php"); ?>
		
	</head>
  
	<body>

<script type="text/javascript" src="bower_components/xlsx-populate/browser/xlsx-populate.min.js"></script>
<script type="text/javascript">

var dateDiv = document.getElementById('divDate');

Object.defineProperty(Date.prototype, 'YYYYMMDDHHMMSS', {
    value: function() {
        function pad2(n) {  // always returns a string
            return (n < 10 ? '0' : '') + n;
        }

        return this.getFullYear() +
               pad2(this.getMonth() + 1) + 
               pad2(this.getDate()) +
               pad2(this.getHours()) +
               pad2(this.getMinutes()) +
               pad2(this.getSeconds());
    }
});

    // Promise is not defined in IE so xlsx-populate uses a polyfill via JSZip.
    var Promise = XlsxPopulate.Promise;
    window.idFileToGet = -1;
    window.file_modello = "";
    window.file_sorgente = "";

    var radioBlank = document.getElementById("radio-blank");
    var radioAjax = document.getElementById("radio-ajax");
    var radioLocal = document.getElementById("radio-local");
    var urlInput = document.getElementById("url-input");
    var fileInput = document.getElementById("file-input");

    function getWorkbook() {
        return new Promise(function (resolve, reject) {
        	$.ajax({
			     async: false,
			     type: 'GET',
			     data: { idfile: window.idFileToGet },
			     url: 'nomemodello.php',
			     success: function(data) {
				    window.file_modello = data;
			     }
			});
			if(window.file_modello==""){
				alert("Impossibile trovare il file del modello");
				return;
			}

            var req = new XMLHttpRequest();
            var url = window.file_modello;
            req.open("GET", url, true);
            req.responseType = "arraybuffer";
            req.onreadystatechange = function () {
                if (req.readyState === 4){
                    if (req.status === 200) {
                        resolve(XlsxPopulate.fromDataAsync(req.response));
                    } else {
                        reject("Received a " + req.status + " HTTP code.");
                    }
                }
            };
            req.send();
        });
    }

    function getFilesorgente(dato_x) {
        return new Promise(function (resolve, reject) {
        	$.ajax({
			     async: false,
			     type: 'GET',
			     data: { idfile: window.idFileToGet },
			     url: 'nomesorgente.php',
			     success: function(data) {
				    window.file_sorgente = data;
			     }
			});
			if(window.file_modello==""){
				alert("Impossibile trovare il file sorgente");
				return;
			}

            var req = new XMLHttpRequest();
            var url = window.file_sorgente;
            req.open("GET", url, true);
            req.responseType = "arraybuffer";
            req.onreadystatechange = function () {
                if (req.readyState === 4){
                    if (req.status === 200) {
                        resolve(XlsxPopulate.fromDataAsync(req.response));
                    } else {
                        reject("Received a " + req.status + " HTTP code.");
                    }
                }
            };
            req.send();
        });
    }

    function generate(type) {
        return getWorkbook()
            .then(function (workbook) {

	        	//Carico i dati da DB
				$.ajax({
				     async: false,
				     type: 'GET',
				     data: { idfile: window.idFileToGet },
				     url: 'datidadb.php',
				     success: function(data) {
				     	if(data!=""){
						    dati = JSON.parse(data);

						    //Mi serve dopo, è il foglio selezionato dal file sorgente
				    	    window.schedaPaeseSorgente = dati.schedaPaeseSorgente;
				    	    //console.log(window.schedaPaeseSorgente);

				    	    sostituzioni = dati.sostituzioni;
				    	    //console.log(sostituzioni);
				    
						    var index;
							for (index = 0; index < sostituzioni.length; ++index) {
							    //console.log(dati[index].sheet);

							    //Controllo che il foglio esista prima di sovrascrivere
							    if(workbook.sheet(sostituzioni[index].sheet)){
									workbook.sheet(sostituzioni[index].sheet)
										.cell(sostituzioni[index].cell)
										.value(sostituzioni[index].value);						    	
							    }
							}
				     	}
				     }
				});

				//Recupero il file sorgente per copiare i dati di origine
				return getFilesorgente(workbook)
		            .then(function (sorgente) {
	            		
	            		//Qui ho la possibilità di accedere sia a 'sorgente' che a 'workbook'

			    	    //console.log(window.schedaPaeseSorgente);

		    	    	//Cover
			    	    r = sorgente.sheet("Cover").range("A3:U12").value();
			    	    workbook.sheet("Cover").range("A6:U15").value("");
			    	    workbook.sheet("Cover").range("A6:U15").value(r);
			    	    //console.log(r);


		    	    	//Worlddata
			    	    r = sorgente.sheet("Worlddata").range("A4:D14").value();
			    	    workbook.sheet("Worlddata").range("C9:F19").value("");
			    	    workbook.sheet("Worlddata").range("C9:F19").value(r);


		    	    	//CountryData
		    	    	valore = sorgente.sheet("Countrydata").cell("A2").value();
		    	    	workbook.sheet("CountryData").cell("C6").value(valore);
			    	    r = sorgente.sheet("Countrydata").range("A4:D14").value(); //qui va bene minuscolo
			    	    workbook.sheet("CountryData").range("C9:F19").value("");
			    	    workbook.sheet("CountryData").range("C9:F19").value(r);

			    	    //ilquadlegenda
			    	    valore = String( sorgente.sheet("Cover").cell("S3").value() );
			    	    valore = valore.replace(",", ".");

			    	    val_numb = (parseFloat(valore)*100).toFixed(1);
		    	    	testo = val_numb + "% per il prodotto selezionato, nei mercati DRIVER e DEFENSE saranno indicati i Paesi che hanno";
						workbook.sheet("Ilquadlegenda").cell("B30").value(testo);


		    	    	//WorldExport
			    	    r = sorgente.sheet("ExportData").range("A4:D14").value();
			    	    workbook.sheet("WorldExport").range("C9:F19").value("");
			    	    workbook.sheet("WorldExport").range("C9:F19").value(r);


		    	    	//Ilquadrante
			    	    r = sorgente.sheet("Mercati rilevanti").range("B5:B18").value();
			    	    workbook.sheet("Ilquadrante").range("D10:D23").value("");
			    	    workbook.sheet("Ilquadrante").range("D10:D23").value(r);

			    	    r = sorgente.sheet("Mercati rilevanti").range("H5:H18").value();
			    	    workbook.sheet("Ilquadrante").range("H10:H23").value("");
			    	    workbook.sheet("Ilquadrante").range("H10:H23").value(r);

			    	    r = sorgente.sheet("Mercati rilevanti").range("B20:B33").value();
			    	    workbook.sheet("Ilquadrante").range("D27:D40").value("");
			    	    workbook.sheet("Ilquadrante").range("D27:D40").value(r);

			    	    r = sorgente.sheet("Mercati rilevanti").range("H20:H33").value();
			    	    workbook.sheet("Ilquadrante").range("H27:H40").value("");
			    	    workbook.sheet("Ilquadrante").range("H27:H40").value(r);

			    	    r = sorgente.sheet("Mercati rilevanti").range("P8:P18").value();
			    	    workbook.sheet("Ilquadrante").range("O12:O22").value("");
			    	    workbook.sheet("Ilquadrante").range("O12:O22").value(r);

			    	    r = sorgente.sheet("Mercati rilevanti").range("P23:P33").value();
			    	    workbook.sheet("Ilquadrante").range("O29:O39").value("");
			    	    workbook.sheet("Ilquadrante").range("O29:O39").value(r);


			    	    //Il sorgente qui non è fisso ma dipende dalla scheda paese scelta
		    	    	//Ilquadrante
			    	    r = sorgente.sheet(window.schedaPaeseSorgente).range("A2:U28").value();
			    	    workbook.sheet("scheda01").range("B4:V30").value("");
			    	    workbook.sheet("scheda01").range("B4:V30").value(r);
			    	    r = String( sorgente.sheet(window.schedaPaeseSorgente).cell("A2").value() );
			    	    workbook.sheet("scheda01").cell("K1").value(r.toUpperCase());
			    	    workbook.sheet("Indice").cell("E27").value(r);


			    	    //SchedaLeg1
			    	    workbook.sheet("SchedaLeg1").range("B4:V14").value("");
			    	    r = sorgente.sheet(window.schedaPaeseSorgente).range("A2:U12").value();
			    	    workbook.sheet("SchedaLeg1").range("B4:V14").value(r);
			    	    //SchedaLeg2
			    	    workbook.sheet("SchedaLeg2").range("B6:V15").value("");
			    	    r = sorgente.sheet(window.schedaPaeseSorgente).range("A14:U23").value();
			    	    workbook.sheet("SchedaLeg2").range("B6:V15").value(r);
			    	    //SchedaLeg3
			    	    workbook.sheet("SchedaLeg3").range("B6:V9").value("");
			    	    r = sorgente.sheet(window.schedaPaeseSorgente).range("A25:U28").value();
			    	    workbook.sheet("SchedaLeg3").range("B6:V9").value(r);


			    	    //MercOppEXCEL
			    	    workbook.sheet("MercOppEXCEL").range("B15:BF24").value("");
			    	    r = sorgente.sheet("Ierscore").range("A5:B14").value();
			    	    workbook.sheet("MercOppEXCEL").range("B15:C24").value(r);
			    	    r = sorgente.sheet("Ierscore").range("C5:C14").value();
			    	    workbook.sheet("MercOppEXCEL").range("E15:E24").value(r);
			    	    r = sorgente.sheet("Ierscore").range("N5:N14").value();
			    	    workbook.sheet("MercOppEXCEL").range("P15:P24").value(r);
			    	    r = sorgente.sheet("Ierscore").range("Y5:Y14").value();
			    	    workbook.sheet("MercOppEXCEL").range("AA15:AA24").value(r);
			    	    r = sorgente.sheet("Ierscore").range("AJ5:AJ14").value();
			    	    workbook.sheet("MercOppEXCEL").range("AL15:AL24").value(r);
			    	    r = sorgente.sheet("Ierscore").range("AU5:AU14").value();
			    	    workbook.sheet("MercOppEXCEL").range("AW15:AW24").value(r);

		    	    	/*
			    	    testo = workbook.sheet("MercOppEXCEL").cell("C27");
		    	    	console.log(testo);
						*/

		                return workbook.outputAsync({ type: type });

		            });

            });
    }


    function generateBlob(idfile) {
	    window.file_modello = "";
	    window.file_sorgente = "";
	    window.schedaPaeseSorgente = "";

    	window.idFileToGet = idfile;
        return generate()
            .then(function (blob) {
                if (window.navigator && window.navigator.msSaveOrOpenBlob) {
                    window.navigator.msSaveOrOpenBlob(blob, "out.xlsx");
                } else {
                    var url = window.URL.createObjectURL(blob);
                    var a = document.createElement("a");
                    document.body.appendChild(a);

                    nomefilesorg = window.file_sorgente.substring(window.file_sorgente.lastIndexOf("/")+1);
                    nomefile = new Date().YYYYMMDDHHMMSS() + nomefilesorg;
                    a.href = url;
                    a.download = nomefile;
                    a.click();
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(a);
                }
            })
            .catch(function (err) {
                alert(err.message || err);
                throw err;
            });
    }

    /*
    function generateBase64() {
        return generate("base64")
            .then(function (base64) {
                if (window.navigator && window.navigator.msSaveOrOpenBlob) {
                    throw new Error("Navigating to data URI is not supported in IE.");
                } else {
                    location.href = "data:" + XlsxPopulate.MIME_TYPE + ";base64," + base64;
                }
            })
            .catch(function (err) {
                alert(err.message || err);
                throw err;
            });
    }
    */
</script>



		<div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->

		<?php require_once("sidebar_messages.php"); ?>
		
		<div id="main-content"> <!-- Main Content Section with everything -->
			
            <?php require_once("top_bar.php"); ?>

            <div id="result"></div>
			
			<div class="content-box"><!-- Start Content Box -->
				
				<div class="content-box-header">
					
					<h3>Elenco Sezioni</h3>
					<script type="text/javascript">
					function selectsezione(){
						var ids=document.getElementById("idsezione").value;
						window.location.href="files_gest.php?ids="+ids;

					}	

					</script>
					<select id="idsezione"  name="id_sezione" value="<?=$row_Recordset1['id_sezione']?>" onchange="selectsezione()" style="margin: 10px">
               			<option value="0">Tutti</option>
               			<?php
               				foreach ($arr_sezioni as $key => $value) {
               			?>
               			<option value="<?=$key?>" <?php if($key==$ids) { ?> selected <?php } ?>><?=$value?></option>
 
               			<?php
               				}
               			?>
            			
               		</select>
					
					<ul class="content-box-tabs">
						<li><a href="#tab1" class="default-tab">Elenco</a></li>
					</ul>
					
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
					
					<div class="tab-content default-tab" id="tab1"> <!-- This is the target div. id must match the href of this div's tab -->
						<!--
						<div class="notification attention png_bg">
							<a href="#" class="close"><img src="resources/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
							<div>
								This is a Content Box. You can put whatever you want in it. By the way, you can close this notification with the top-right cross.
							</div>
						</div>
						-->
                        <div class="notification information png_bg">
                            <a href="#" class="close"><img src="resources/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
							<div>
								Tramite la tabella sottostante puoi inserire / modificare / eliminare l'elenco dei report generati.</div>
						</div>


					<form name="ordinamento" id="ordinamento" action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="riordina" value="1" />
                                              
						<table>
							
							<thead>
								<tr>
                                	<th><input type="submit" value="Salva" /></th>
                                	<th>Nome</th>
	                                <th>Data generazione</th>
	                                <th>File Sorgente</th>
	                                <th>File Modello</th>
	                                <th>Genera File</th>
	                                <th>&nbsp;</th>
								</tr>
								
							</thead>
						 
							<tfoot>
								<tr>
									<td colspan="7">
										<div class="bulk-actions align-left">
												<a href="<?php echo $sufx_sezione; ?>_add.php">Aggiungi nuovo</a>
	                               	    <!--
										<select name="dropdown">
												<option value="option1">Choose an action...</option>
												<option value="option2">Edit</option>
												<option value="option3">Delete</option>
											</select>
											<a class="button" href="#">Apply to selected</a>
                                        -->
										</div>
                                        
                                        
                                        
                                      <div class="pagination">
                                      	
                                        <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, 0, $queryString_Recordset1); ?>" title="First Page">&laquo; First</a><a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, max(0, $pageNum_Recordset1 - 1), $queryString_Recordset1); ?>" title="Previous Page">&laquo; Previous</a>
						<?php for($i=0;$i<$totalPages_Recordset1+1;$i++){ 
							if($i==$pageNum_Recordset1){ ?>
			                    <a href="#" class="number current" title="<?php echo $i+1; ?>"><?php echo $i+1; ?></a>	
						<?php }else{ ?>
        		                <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, $i, $queryString_Recordset1); ?>" class="number" title="<?php echo $i+1; ?>"><?php echo $i+1; ?></a>
                        <?php
							}
						} ?>
                            

                                        <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, min($totalPages_Recordset1, $pageNum_Recordset1 + 1), $queryString_Recordset1); ?>" title="Next Page">Next &raquo;</a><a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, $totalPages_Recordset1, $queryString_Recordset1); ?>" title="Last Page">Last &raquo;</a>
                                      </div> 
                                      <!-- End .pagination -->
                                    <div class="clear"></div>
                                  </td>
								</tr>
							</tfoot>
						 
							<tbody>
<?php if ($totalRows_Recordset1 > 0) { // Show if recordset not empty 
?>
    <?php do { ?>
  <tr <?php if (!(strcmp($row_Recordset1['is_attivo'],"0"))) {echo 'class="not_attivo"';} ?>>
    <td><input type="text" name="ordinamento<?php echo $row_Recordset1['idc']; ?>" value="<?php echo $row_Recordset1['ordinamento']; ?>" size="4" maxlength="5" style="text-align:center" /></td>
    <td><?php echo $row_Recordset1['nome']; ?></td>
    <td><?php echo data_a_video($row_Recordset1['modified'],1); ?></td>
    <td>
    	<?php 
    	$filetolink = $_SESSION["path_upload_admin"] . $_SESSION["path_filecaricati"] . $row_Recordset1["fileimg"];
    	if( (strcmp($row_Recordset1["fileimg"],NULL)!=0) && (file_exists($filetolink)) ){
    	?>
       	<a title="Scarica file originale" target="_blank" href="<?=$filetolink?>"><?=$row_Recordset1["fileimg"]?></a>
		<?php }else{
			echo "-";
			}
		?>
	</td> 
    <td>
    	<?php 
    	$filetolink = $_SESSION["path_upload_admin"] . $_SESSION["path_filemodelli"] . $row_Recordset1["filemodello"];
    	if( (strcmp($row_Recordset1["filemodello"],NULL)!=0) && (file_exists($filetolink)) ){ 
    	?>
       	<a title="Scarica file modello" target="_blank" href="<?=$filetolink?>"><?=$row_Recordset1["filemodello"]?></a>
		<?php }else{
				echo "-";
			}
		?>
	</td> 
	<td> <button onclick="generateBlob(<?=$row_Recordset1['idc']?>)" type="button">Genera e Scarica</button> </td>
    <td>
      <!-- Icons -->
      <!-- a onclick="generateBlob(<?=$row_Recordset1['idc']?>)" href="#" title="Genera"><img src="resources/images/icons/hammer_screwdriver.png" alt="Genera" /></a -->

      <a href="<?php echo $sufx_sezione; ?>_edit.php?idc=<?php echo $row_Recordset1['idc']; ?>" title="Edit"><img src="resources/images/icons/pencil.png" alt="Edit" /></a>
      <a href="<?php echo $sufx_sezione; ?>_gest.php?elimina=1&idc=<?php echo $row_Recordset1['idc']; ?>" title="Delete" onclick="return confirm('Eliminare il record?');"><img src="resources/images/icons/cross.png" alt="Delete" /></a> 
    </td>
  </tr>
  <?php } while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1)); ?>
<?php } // Show if recordset not empty ?>
<?php if ($totalRows_Recordset1 == 0) { // Show if recordset empty ?>
  <tr>
    <td>&nbsp;</td>
    <td colspan="5">Nessun record in elenco</td>
    <td>&nbsp;</td>
  </tr>
  <?php } // Show if recordset empty ?>
                            </tbody>
							
						</table>
					</form>
                       
						
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
