<?php

function isLoggedIn(){
	if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']){
		return true;
	}else{
		return false;
	}
}

function setToken(){
    $file = 'tokens/'.$_SESSION['type'].'-'.$_SESSION['social_id'].'.token';
    $token = generateRandomString(3).generateRandomString(10).generateRandomString(4);
    file_put_contents($file,$token);
    $_SESSION['token'] = $token;
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}



function createImage($photo,$type,$social_id){
	$template = '../images/template.png';
	$photo_path = $photo;
	list($width,$height) = getimagesize($photo);

	$template = imagecreatefromstring(file_get_contents($template));
	$photo = imagecreatefromstring(file_get_contents($photo));

	$face_width = 400;
	$face_height = 400;

	$photo1 = imagecreatetruecolor($face_width, $face_height);
	imagealphablending($photo1, true);
	imagecopyresampled($photo1, $photo, 0, 0, 0, 0, $face_width, $face_height, $face_width, $face_height);
	$mask = imagecreatetruecolor($face_width,$face_height);
	$transparent = imagecolorallocate($mask, 255, 0, 0);
	imagecolortransparent($mask,$transparent);
	imagefilledellipse($mask, $face_width/2, $face_height/2, $face_width, $face_height, $transparent);
	$red = imagecolorallocate($mask,0,0,0);
	imagecopymerge($photo1,$mask,0,0,0,0,$face_width,$face_height,100);
	imagecolortransparent($photo1,$red);
	imagefill($photo1,0,0,$red);
	header('Content-Type: image/png');
	//imagepng($photo1);
	//die;

	imagecopymerge($template, $photo1, 1392, 315, 0, 0, $face_width, $face_height, 100);
	$file = "../users/".$type[0].$social_id.'.png';
	imagepng($template, $file);
}