<?php
if(isset($_FILES['design_box_image'])){
	require_once("../../../../wp-load.php");
	$target_dir = get_temp_dir();
	$target_filename = date('Ymd').'-'.basename($_FILES['design_box_image']['name']);
	$check = getimagesize($_FILES['design_box_image']['tmp_name']);
	if($check){
		$imageData = file_get_contents($_FILES['design_box_image']['tmp_name']);
		echo sprintf('data:image/png;base64,%s', base64_encode($imageData));
	}
	//if($check !== false && move_uploaded_file($_FILES['design_box_image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].$target_dir.$target_filename)) echo $target_dir.$target_filename;
	//else echo 'error';
}