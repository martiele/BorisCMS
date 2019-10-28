<?php

	$stringa_errori_file = "";
	$uploaddir = $_SESSION["path_upload_admin"];
	//first include the photo class	
	@include 'photo.php';	
	
	//Foto - inizio
	$directory = $uploaddir.$_SESSION["path_foto_prodotto"];
	$nomeCampoForm = "img";
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
			if($photo->getWidth() > 1024 || $photo->getHeight() > 1024) {
				$errors = $photo->doResize(1024,1024,$directory.$_SESSION["path_fotooriginal_prodotto"]);
				$photo2 = new Photo(array('name'=>$nome_file_inserito,'tmp_name'=>$directory.$_SESSION["path_fotooriginal_prodotto"].$nome_file_inserito));
				// we pass the width and height we want to crop to to the doCenterCrop function
				$width = 1024;
				$height = 1024;
				$errors = $photo2->doCenterCrop($width,$height,$directory.$_SESSION["path_fotobig_prodotto"]);
				unset($photo2);
			}else {
			   // the photo's not too big, so we'll just move it, passing the directory
			   // where we want it to go to the move function
				$errors = $photo->copia($directory.$_SESSION["path_fotooriginal_prodotto"]);
			}
			
			if($photo->getWidth() > 407 || $photo->getHeight() > 407) {
				$errors = $photo->doResize(407,407,$directory.$_SESSION["path_fotobig_prodotto"],1);
				$photo2 = new Photo(array('name'=>$nome_file_inserito,'tmp_name'=>$directory.$_SESSION["path_fotobig_prodotto"].$nome_file_inserito));
				// we pass the width and height we want to crop to to the doCenterCrop function
				$width = 407;
				$height = 407;
				$errors = $photo2->doCenterCrop($width,$height,$directory.$_SESSION["path_fotobig_prodotto"]);
				unset($photo2);
			}else {
			   // the photo's not too big, so we'll just move it, passing the directory
			   // where we want it to go to the move function
			   $errors = $photo->copia($directory.$_SESSION["path_fotobig_prodotto"]);
			}

			if($photo->getWidth() > 248 || $photo->getHeight() > 248) {
				$errors = $photo->doResize(248,248,$directory.$_SESSION["path_fotosmall_prodotto"],1);
				$photo2 = new Photo(array('name'=>$nome_file_inserito,'tmp_name'=>$directory.$_SESSION["path_fotosmall_prodotto"].$nome_file_inserito));
				// we pass the width and height we want to crop to to the doCenterCrop function
				$width = 248;
				$height = 248;
				$errors = $photo2->doCenterCrop($width,$height,$directory.$_SESSION["path_fotosmall_prodotto"]);
				unset($photo2);
			}else {
			   // the photo's not too big, so we'll just move it, passing the directory
			   // where we want it to go to the move function
			   $errors = $photo->copia($directory.$_SESSION["path_fotosmall_prodotto"]);
			}
			
		}
		if(count($errors) == 0 ) {
			$updateSQL = sprintf("UPDATE dny_prodotto SET nomefile=%s WHERE id=%s LIMIT 1",
				GetSQLValueString($nome_file_inserito, "text"),
				GetSQLValueString($id_inserito, "int"));
			mysqli_select_db($std_conn, $database_std_conn);
			$Result1 = mysqli_query($std_conn, $updateSQL) or die(mysqli_error($std_conn));
		}	
		unset($photo);
		$curr++;
	}

	//Foto - fine

?>