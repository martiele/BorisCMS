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
	//echo '		<li><a href="linee_gest.php">Linee</a></li>';
	$lista = "";
	switch($ambito){
		case "taglie_colori":
		case "foto_prodotto":
			mysqli_select_db($std_conn, $database_std_conn);
			$tabella = "dny_prodotto";
			$label_sup = "id_sottocategoria";
			$pagina = "prodotti_edit.php?id";
			$query_rsc = sprintf("SELECT nome FROM %s WHERE id_lingua=%s AND id_ref=%s",
				$tabella."_lingua",
				GetSQLValueString($_SESSION["linguadefault"],"int"),
				GetSQLValueString($val,"int"));
			$rsc = mysqli_query($std_conn, $query_rsc) or die(mysqli_error($std_conn));
			if($row_rsc = mysqli_fetch_assoc($rsc)){
				$nome = $row_rsc["nome"];
			}else{
				$nome = "Prodotto";
			}
			mysqli_free_result($rsc);
			if($tipo=="gest"){
				if($ambito=="foto_prodotto"){
					$lista = sprintf('<li>Gestione Fotografie</li>') . $lista;
				}else{
					$lista = sprintf('<li>Gestione Taglie e Colori</li>') . $lista;					
				}
			}
			if($tipo=="add"){
				if($ambito=="foto_prodotto"){
					$lista = sprintf('<li>Aggiungi Foto Prodotto</a></li>') . $lista;
					$lista = sprintf('<li><a href="foto_prodotto_gest.php?prodotto=%s">Gestione Fotografie</a></li>',$val) . $lista;
				}else{
					$lista = sprintf('<li>Aggiungi Variante Taglia/Colore</a></li>') . $lista;
					$lista = sprintf('<li><a href="taglie_colori_gest.php?prodotto=%s">Gestione Taglie e Colori</a></li>',$val) . $lista;					
				}
			}
			if($tipo=="edit"){
				if($ambito=="foto_prodotto"){
					$lista = sprintf('<li>Modifica Foto Prodotto</a></li>') . $lista;
					$lista = sprintf('<li><a href="foto_prodotto_gest.php?prodotto=%s">Gestione Fotografie</a></li>',$val) . $lista;
				}else{
					$lista = sprintf('<li>Modifica Variante Taglia/Colore</a></li>') . $lista;
					$lista = sprintf('<li><a href="taglie_colori_gest.php?prodotto=%s">Gestione Taglie e Colori</a></li>',$val) . $lista;					
				}
			}
			//recupero l'id precedente
			$query_rsc = sprintf("SELECT %s FROM %s WHERE id=%s",
				$label_sup,
				$tabella,
				GetSQLValueString($val,"int"));
			$rsc = mysqli_query($std_conn, $query_rsc) or die(mysqli_error($std_conn));
			if($row_rsc = mysqli_fetch_assoc($rsc)){
				$extraURL="&sottocategoria=".$row_rsc[$label_sup];
				$lista = sprintf('<li><a href="%s=%s%s">%s</a></li>',$pagina,$val,$extraURL,$nome) . $lista;
				$tipo = "prec";
				$val = $row_rsc[$label_sup];
			}else{
				exit;
			}
			mysqli_free_result($rsc);
			
		case "prodotti":
			mysqli_select_db($std_conn, $database_std_conn);
			$tabella = "dny_sottocategoria";
			$label_sup = "id_categoria";
			$pagina = "prodotti_gest.php?sottocategoria";
			$query_rsc = sprintf("SELECT nome FROM %s WHERE id_lingua=%s AND id_ref=%s",
				$tabella."_lingua",
				GetSQLValueString($_SESSION["linguadefault"],"int"),
				GetSQLValueString($val,"int"));
			$rsc = mysqli_query($std_conn, $query_rsc) or die(mysqli_error($std_conn));
			if($row_rsc = mysqli_fetch_assoc($rsc)){
				$nome = $row_rsc["nome"];
			}else{
				$nome = "Sottocategoria";
			}
			mysqli_free_result($rsc);
			if($tipo=="gest"){
				$lista = sprintf('<li>%s</li>',$nome) . $lista;
			}else{
				if($tipo=="add"){
					$lista = sprintf('<li>Nuovo Prodotto</a></li>') . $lista;
				}
				if($tipo=="edit"){
					$lista = sprintf('<li>Modifica Prodotto</a></li>') . $lista;
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
			
		case "sottocategorie":
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
				$nome = "Sottocategoria";
			}
			mysqli_free_result($rsc);
			if($tipo=="gest"){
				$lista = sprintf('<li>%s</li>',$nome) . $lista;
			}else{
				if($tipo=="add"){
					$lista = sprintf('<li>Nuova Sottocategoria</a></li>') . $lista;
				}
				if($tipo=="edit"){
					$lista = sprintf('<li>Modifica Sottocategoria</a></li>') . $lista;
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
			
		case "categorie":
			mysqli_select_db($std_conn, $database_std_conn);
			$tabella = "dny_linea";
			$label_sup = "";
			$pagina = "categorie_gest.php?linea";
			$query_rsc = sprintf("SELECT nome FROM %s WHERE id_lingua=%s AND id_ref=%s",
				$tabella."_lingua",
				GetSQLValueString($_SESSION["linguadefault"],"int"),
				GetSQLValueString($val,"int"));
			$rsc = mysqli_query($std_conn, $query_rsc) or die(mysqli_error($std_conn));
			if($row_rsc = mysqli_fetch_assoc($rsc)){
				$nome = $row_rsc["nome"];
			}else{
				$nome = "Categoria";
			}
			mysqli_free_result($rsc);
			if($tipo=="gest"){
				$lista = sprintf('<li>%s</li>',$nome) . $lista;
			}else{
				if($tipo=="add"){
					$lista = sprintf('<li>Nuova Categoria</a></li>') . $lista;
				}
				if($tipo=="edit"){
					$lista = sprintf('<li>Modifica Categoria</a></li>') . $lista;
				}
				$lista = sprintf('<li><a href="%s=%s">%s</a></li>',$pagina,$val,$nome) . $lista;
			}

			echo $lista;	
	}
	echo '	</ul>';
	echo '</div>';
}

?>