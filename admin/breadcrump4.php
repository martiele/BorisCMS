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
		case "foto_lookbook":
			mysqli_select_db($std_conn, $database_std_conn);
			$tabella = "dny_lookbook";
			$pagina = "lookbook_edit.php?id";
			$query_rsc = sprintf("SELECT nome FROM %s WHERE id_lingua=%s AND id_ref=%s",
				$tabella."_lingua",
				GetSQLValueString($_SESSION["linguadefault"],"int"),
				GetSQLValueString($val,"int"));
			$rsc = mysqli_query($std_conn, $query_rsc) or die(mysqli_error($std_conn));
			if($row_rsc = mysqli_fetch_assoc($rsc)){
				$nome = $row_rsc["nome"];
			}else{
				$nome = "Lookbook";
			}
			mysqli_free_result($rsc);
			if($tipo=="gest"){
				$lista = sprintf('<li>Gestione Fotografie</li>') . $lista;
			}
			if($tipo=="add"){
				$lista = sprintf('<li>Aggiungi Foto Lookbook</a></li>') . $lista;
				$lista = sprintf('<li><a href="foto_lookbook_gest.php?prodotto=%s">Gestione Fotografie</a></li>',$val) . $lista;
			}
			if($tipo=="edit"){
				$lista = sprintf('<li>Modifica Foto Lookbook</a></li>') . $lista;
				$lista = sprintf('<li><a href="foto_lookbook_gest.php?prodotto=%s">Gestione Fotografie</a></li>',$val) . $lista;
			}
			//recupero l'id precedente
			$lista = sprintf('<li><a href="%s=%s">%s</a></li>',$pagina,$val,$nome) . $lista;
			$tipo = "prec";
			
		case "lookbook":
			$pagina = "lookbook_gest.php";
			$nome = "Lookbook";
			if($tipo=="gest"){
				$lista = sprintf('<li>%s</li>',$nome) . $lista;
			}else{
				if($tipo=="add"){
					$lista = sprintf('<li>Nuova composizione</a></li>') . $lista;
				}
				if($tipo=="edit"){
					$lista = sprintf('<li>Modifica composizione</a></li>') . $lista;
				}
				$lista = sprintf('<li><a href="%s">%s</a></li>',$pagina,$nome) . $lista;
			}		
			
			echo $lista;	
	}
	echo '	</ul>';
	echo '</div>';
}

?>