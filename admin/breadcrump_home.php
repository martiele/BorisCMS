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
	$lista = "";
	switch($ambito){
		case "homepage":
			mysqli_select_db($std_conn, $database_std_conn);
			$tabella = "dny_categoria";
			$label_sup = "id_linea";
			$pagina = "sottocategorie_gest.php?categoria";
			$query_rsc = sprintf("SELECT nome FROM %s WHERE id_lingua=%s AND id_ref=%s",
				$tabella."_lingua",
				GetSQLValueString($_SESSION["linguadefault"],"int"),
				GetSQLValueString($val,"int"));
			$rsc = mysqli_query($std_conn, $query_rsc) or die(mysqli_error($std_conn));
			if($row_rsc = mysqli_fetch_assoc($rsc)){
				$nome = $row_rsc["nome"];
			}else{
				$nome = "Slide";
			}
			mysqli_free_result($rsc);
			if($tipo=="gest"){
				$lista = sprintf('<li>%s</li>',$nome) . $lista;
			}else{
				if($tipo=="add"){
					$lista = sprintf('<li>Nuova Slide</a></li>') . $lista;
				}
				if($tipo=="edit"){
					$lista = sprintf('<li>Modifica Slide</a></li>') . $lista;
				}
				$lista = sprintf('<li><a href="%s=%s">%s</a></li>',$pagina,$val,$nome) . $lista;
			}		
			//recupero l'id precedente
			$query_rsc = sprintf("SELECT %s FROM %s WHERE id=%s",
				$label_sup,
				$tabella,
				GetSQLValueString($val,"int"));
			$rsc = mysqli_query($std_conn, $query_rsc) or die(mysqli_error($std_conn));
			if($row_rsc = mysqli_fetch_assoc($rsc)){
				$tipo = "prec";
				$val = $row_rsc[$label_sup];
			}else{
				exit;
			}
			mysqli_free_result($rsc);
			
			echo $lista;	
	}
	echo '	</ul>';
	echo '</div>';
}

?>