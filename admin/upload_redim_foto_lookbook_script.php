<?php

	$stringa_errori_file = "";
	$uploaddir = $_SESSION["path_upload_admin"];
	//first include the photo class	
	@include 'photo.php';	
	
	//Foto - inizio
	//Recupero il prossimo num ordinamento
	mysqli_select_db($std_conn, $database_std_conn);
	$query_nextOrdinamento = sprintf("SELECT MAX(ordinamento) massimo FROM dny_foto_lookbook WHERE id_prodotto=%s", GetSQLValueString($id_inserito, "int"));
	$nextOrdinamento = mysqli_query($std_conn, $query_nextOrdinamento) or die(mysqli_error($std_conn));
	$row_nextOrdinamento = mysqli_fetch_assoc($nextOrdinamento);
	$totalRows_nextOrdinamento = mysqli_num_rows($nextOrdinamento);
	if(($totalRows_nextOrdinamento>0)&&($row_nextOrdinamento["massimo"]!="")){
		$curr = $row_nextOrdinamento["massimo"]+1;
	}else{
		$curr = 1;
	}
	mysqli_free_result($nextOrdinamento);
	
	for($i=1;$i<=$_SESSION["n_img_prodotto"];$i++){
		$directory = $uploaddir.$_SESSION["path_foto_lookbook"];
		$nomeCampoForm = "img".$i;
		$nextID = $id_inserito;
		if(isset($_FILES[$nomeCampoForm])) {
			//pass the image array to the photo class constructor
			$photo = new Photo($_FILES[$nomeCampoForm]);
			$thefile = preg_replace('/[^(A-Za-z0-9.)]*/','', $_FILES[$nomeCampoForm]['name']);
			$nome_file_inserito = "p".$nextID . "_n" . $curr . "_" . $thefile;
			$photo->name = $nome_file_inserito;
			// validate the uploaded file to make sure it is indeed an image and doesn't
			// violate any size restrictions you place on uploads
			if(count($errors = $photo->validate()) == 0) {
				// if it is valid, we'll make the thumb, passing the thumb size (50 px)
				// and the directory where we want to store it
				if($photo->getWidth() > 900 || $photo->getHeight() > 1200) {
					$errors = $photo->doResize(900,1200,$directory.$_SESSION["path_fotooriginal_prodotto"]);
					$photo2 = new Photo(array('name'=>$nome_file_inserito,'tmp_name'=>$directory.$_SESSION["path_fotooriginal_prodotto"].$nome_file_inserito));
					// we pass the width and height we want to crop to to the doCenterCrop function
					$width = 900;
					$height = 1200;
					$errors = $photo2->doCenterCrop($width,$height,$directory.$_SESSION["path_fotobig_prodotto"]);
					unset($photo2);
				}else {
				   // the photo's not too big, so we'll just move it, passing the directory
				   // where we want it to go to the move function
					$errors = $photo->move($directory.$_SESSION["path_fotooriginal_prodotto"]);
					unset($photo);
					$photo = new Photo(array('name'=>$nome_file_inserito,'tmp_name'=>$directory.$_SESSION["path_fotooriginal_prodotto"].$nome_file_inserito));
				}
				
				if($photo->getWidth() > 326 || $photo->getHeight() > 435) {
					$errors = $photo->doResize(326,435,$directory.$_SESSION["path_fotobig_prodotto"],1);
					$photo2 = new Photo(array('name'=>$nome_file_inserito,'tmp_name'=>$directory.$_SESSION["path_fotobig_prodotto"].$nome_file_inserito));
					// we pass the width and height we want to crop to to the doCenterCrop function
					$width = 326;
					$height = 435;
					$errors = $photo2->doCenterCrop($width,$height,$directory.$_SESSION["path_fotobig_prodotto"]);
					unset($photo2);
				}else {
				   // the photo's not too big, so we'll just move it, passing the directory
				   // where we want it to go to the move function
				   $errors = $photo->move($directory.$_SESSION["path_fotobig_prodotto"]);
				}

				if($photo->getWidth() > 142 || $photo->getHeight() > 190) {
					$errors = $photo->doResize(142,190,$directory.$_SESSION["path_fotosmall_prodotto"],1);
					$photo2 = new Photo(array('name'=>$nome_file_inserito,'tmp_name'=>$directory.$_SESSION["path_fotosmall_prodotto"].$nome_file_inserito));
					// we pass the width and height we want to crop to to the doCenterCrop function
					$width = 142;
					$height = 190;
					$errors = $photo2->doCenterCrop($width,$height,$directory.$_SESSION["path_fotosmall_prodotto"]);
					unset($photo2);
				}else {
				   // the photo's not too big, so we'll just move it, passing the directory
				   // where we want it to go to the move function
				   $errors = $photo->move($directory.$_SESSION["path_fotosmall_prodotto"]);
				}
				
			}
			if(count($errors) == 0 ) {
				$updateSQL = sprintf("INSERT INTO dny_foto_lookbook (nomefile, ordinamento, id_prodotto) VALUES (%s, %s, %s)",
					GetSQLValueString($nome_file_inserito, "text"),
					GetSQLValueString($curr, "int"),
					GetSQLValueString($id_inserito, "int"));
				mysqli_select_db($std_conn, $database_std_conn);
				$Result1 = mysqli_query($std_conn, $updateSQL) or die(mysqli_error($std_conn));
			}	
			unset($photo);
			$curr++;
		}
	}
	//Foto - fine
	
?>