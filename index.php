<?php

/**
 * Index file for accessing the ImageScaler class
 *
 * @author: Harisankar <mrsank@live.in>
 * @file: index.php
 * @version: 1.0
 */

include 'lib/ImageScaler.php';

$imageResize = new ImageScaler();
$newWidth = 250;    // Proposed width
$newHeight = 180;   // Proposed height
$imageResize->setNewWidth($newWidth);
$imageResize->setNewHeight($newHeight);
$sFilePath = 'file.png';
$imageResize->setImageToResize($sFilePath); // Full Path to the file
$imageResize->setImageRatio(true);
$imageResize->setNewImageName('scaled_image');
$imageResize->setFolderToSave('/'); // Full path to the new file
$imageProcess = $imageResize->resizeImage();
if($imageProcess)
{
    echo "Image created successfully!!!!";
}
else
{
    echo "Image creation failed!!!";
} // if..else...
