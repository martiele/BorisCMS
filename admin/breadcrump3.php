<?php
// Breadcrump

/*
Parametri
1 - ambito (categorie, sottocategorie, prodotti, attributi)
2 - gest, add, edit
3 - valore
*/
function breadcrump($ambito, $tipo, $val){
	global $database_std_conn;
	global $std_conn;
		
	echo '<div class="breadcrumb">';
	echo '	<ul id="crumbs">';
	echo '		<li><a href="nazioni_gest.php">Nazioni</a></li>';
	$lista = "";
	switch($ambito){
		case "provincie":
			mysqli_select_db($std_conn, $database_std_conn);
			$tabella = "dny_nazione";
			$label_sup = "";
			$pagina = "provincie_gest.php?nazione";
			$query_rsc = sprintf("SELECT nome FROM %s WHERE id=%s",
				$tabella,
				GetSQLValueString($val,"int"));
			$rsc = mysqli_query($std_conn, $query_rsc) or die(mysqli_error($std_conn));
			if($row_rsc = mysqli_fetch_assoc($rsc)){
				$nome = $row_rsc["nome"];
			}else{
				$nome = "Provincia";
			}
			mysqli_free_result($rsc);
			if($tipo=="gest"){
				$lista = sprintf('<li>%s</li>',$nome) . $lista;
			}else{
				if($tipo=="add"){
					$lista = sprintf('<li>Nuova Provincia</a></li>') . $lista;
				}
				if($tipo=="edit"){
					$lista = sprintf('<li>Modifica Provincia</a></li>') . $lista;
				}
				$lista = sprintf('<li><a href="%s=%s">%s</a></li>',$pagina,$val,$nome) . $lista;
			}

			echo $lista;	
	}
	echo '	</ul>';
	echo '</div>';
}

?>