<?php

if ($_SERVER["REQUEST_METHOD"] !== 'POST') {
    header('Location: client.html');
    die();
}


//Start Upload Script
define("ACCEPTED_IMG_EXT", ['png', 'jpg', 'jpeg']);

function imagesUpload($imgs, $category = '')
{
    if (empty($imgs['name'][0])) {
        header('Location: client.html');
        die();
    }

    $organizedImages = [];

    //Reorganize images array
    foreach ($imgs as $key => $val) {
        foreach ($val as $key2 => $imgAttribute) {
            $organizedImages[$key2][$key] =  $imgAttribute;
        }
    }

    foreach ($organizedImages as $img) {
        imgUpload($img, $category);
    }
}

function imgUpload($img, $category = '', $single = false)
{

    $ext = explode("/", $img['type'])[1];

    $fileExt = in_array($ext, ACCEPTED_IMG_EXT) ? $ext : false;

    $single = $single ? 'single-' : '';

    if ($fileExt !== false) {

        $dirUploads = empty($category) ? "uploads/" : "uploads/$category/";

        if (!file_exists($dirUploads)) {
            mkdir($dirUploads, 0777, true);
        }

        move_uploaded_file($img['tmp_name'], $dirUploads . $single .  $img['name']);
    }
}

/*
Use one of the following functions for the upload.
*/

//Upload to dir: uploads
//imagesUpload($_FILES['multipleImages']);

//Upload to dir: uploads/$category 
$category = rand();
imagesUpload($_FILES['multipleImages'], $category);
imgUpload($_FILES['singleImage'], $category, true);
header('Location: client.html');
