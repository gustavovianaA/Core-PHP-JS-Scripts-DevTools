<?php

//Verify request method and selected images existance
if ($_SERVER["REQUEST_METHOD"] !== 'POST') {
    header('Location: client.html');
    die();
}

if (empty($_FILES['imagesUpload']['name'][0])) {
    header('Location: client.html');
    die();
}

//Start Upload Script
define("ACCEPTED_IMG_EXT", ['png', 'jpg', 'jpeg']);

function imagesUpload($imgs, $category = '')
{

    $organizedImages = [];

    //Reorganize images array
    foreach ($imgs['imagesUpload'] as $key => $val) {
        foreach ($val as $key2 => $imgAttribute) {
            $organizedImages[$key2][$key] =  $imgAttribute;
        }
    }

    foreach ($organizedImages as $img) {
        if (isset($category))
            imgUpload($img, $category);
        else
            imgUpload($img);
    }
}

function imgUpload($img, $category = '')
{
    $valid = true;

    $ext = explode("/", $img['type'])[1];

    if (in_array($ext, ACCEPTED_IMG_EXT)) {
        $fileExt = $ext;
    } else {
        $valid = false;
    }

    if ($valid === true) {

        $dirUploads = empty($category) ? "uploads/" : "uploads/$category/";

        if (!file_exists($dirUploads)) {
            mkdir($dirUploads, 0777, true);
        }

        move_uploaded_file($img['tmp_name'], $dirUploads . $img['name']);
    }
}

//Upload to dir: uploads
imagesUpload($_FILES);

//Upload to dir: uploads/$category
$category = rand();
imagesUpload($_FILES, $category);
