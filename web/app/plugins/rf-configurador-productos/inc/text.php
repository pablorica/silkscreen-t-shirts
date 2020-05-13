<?php
if(isset($_GET['txt'])){
	$im = imagecreatetruecolor(300, 42);
	$c = explode(',', isset($_GET['rgb']) ? $_GET['rgb'] : '0,0,0');
	$color = imagecolorallocate($im, $c[0], $c[1], $c[2]);
	$transparent = imagecolorallocatealpha($im, 0, 0, 0, 127);
	imageSaveAlpha($im, true);
	imageAlphaBlending($im, false);
	imagefilledrectangle($im, 0, 0, 799, 799, $transparent);
	imagealphablending($im, true);
	$font = '../fonts/'.(isset($_GET['font']) ? $_GET['font'] : 'ARIAL.TTF');
	$text = isset($_GET['txt']) ? $_GET['txt'] : 'Inserte texto aquí';
	imagettftext($im, 40, 0, 1, 41, $color, $font, $text);

	header("Content-type: image/png");
	imagepng($im, NULL, 0, PNG_NO_FILTER);
	imagedestroy($im);

	//$im2 = imagecreatefrompng('tmp.png');
	//$im3 = imagecropauto($im2, IMG_CROP_TRANSPARENT);
	//if($im2 !== false){
	//	imagepng($im2, 'tmp2.png', 0, PNG_NO_FILTER);
	//	imagedestroy($im2);
		//$im = $im2;
	//}
}