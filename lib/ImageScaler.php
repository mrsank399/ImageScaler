<?php

/**
 * Class ImageResize - The class which is created for the purpose of Image resizing
 *
 * @author: Harisankar <mrsank@live.in>
 * @file: ImageScaler.php
 * @package: ImageScaler
 * @access: Public
 * @version: 1.0
 */
class ImageScaler
{
    /**
     * @var string ImageName
     */
    protected $imageToResize;
    /**
     * @var int ImageWidth
     */
    protected $newWidth;
    /**
     * @var int ImageHeight
     */
    protected $newHeight;
    /**
     * @var int Ratio
     */
    protected $imageRatio;
    /**
     * @var string NewImageName
     */
    protected $newImageName;
    /**
     * @var string FolderName
     */
    protected $folderToSave;

    /**
     * @return mixed
     */
    public function getImageToResize()
    {
        return $this->imageToResize;
    }

    /**
     * @param mixed $imageToResize
     */
    public function setImageToResize($imageToResize)
    {
        $this->imageToResize = $imageToResize;
    }

    /**
     * @return mixed
     */
    public function getNewWidth()
    {
        return $this->newWidth;
    }

    /**
     * @param mixed $newWidth
     */
    public function setNewWidth($newWidth)
    {
        $this->newWidth = $newWidth;
    }

    /**
     * @return mixed
     */
    public function getNewHeight()
    {
        return $this->newHeight;
    }

    /**
     * @param mixed $newHeight
     */
    public function setNewHeight($newHeight)
    {
        $this->newHeight = $newHeight;
    }

    /**
     * @return mixed
     */
    public function getImageRatio()
    {
        return $this->imageRatio;
    }

    /**
     * @param mixed $imageRatio
     */
    public function setImageRatio($imageRatio)
    {
        $this->imageRatio = $imageRatio;
    }

    /**
     * @return mixed
     */
    public function getNewImageName()
    {
        return $this->newImageName;
    }

    /**
     * @param mixed $newImageName
     */
    public function setNewImageName($newImageName)
    {
        $this->newImageName = $newImageName;
    }

    /**
     * @return mixed
     */
    public function getFolderToSave()
    {
        return $this->folderToSave;
    }

    /**
     * @param mixed $folderToSave
     */
    public function setFolderToSave($folderToSave)
    {
        $this->folderToSave = $folderToSave;
    }

    /**
     * Image resizer function for resizing the image
     * @return array
     */
    function resizeImage()
    {
        if (!file_exists($this->getImageToResize())) {
            exit("File " . $this->getImageToResize() . " does not exist.");
        } // if

        $fileInfo = GetImageSize($this->getImageToResize());

        if(empty($fileInfo))
        {
            exit("The file " . $this->getImageToResize() . " doesn't seem to be an image.");
        } // if

        $imageWidth = $fileInfo[0];
        $imageHeight = $fileInfo[1];
        $imageMime = $fileInfo['mime'];

        if ($this->getImageRatio())
        {
            $imageThumb = ($this->getNewWidth() < $imageWidth && $this->getNewHeight() < $imageHeight) ? true : false; // Thumbnail
            $largeImage = ($this->getNewWidth() > $imageWidth || $this->getNewHeight() > $imageHeight) ? true : false; // Bigger Image

            if ($imageThumb)
            {
                if ($this->getNewWidth() >= $this->getNewHeight())
                {
                    $x = ($imageWidth / $this->getNewWidth());
                    $iNewHeight = $imageHeight / $x;
                    $this->setNewHeight($iNewHeight);
                }
                else if ($this->getNewHeight() >= $this->getNewWidth())
                {
                    $x = ($imageHeight / $this->getNewHeight());
                    $iNewWidth = $imageWidth / $x;
                    $this->setNewWidth($iNewWidth);
                } // if...else...
            }
            else if ($largeImage)
            {
                if ($this->getNewWidth() >= $imageWidth)
                {
                    $x = ($this->getNewWidth() / $imageWidth);
                    $iNewHeight = $imageHeight / $x;
                    $this->setNewHeight($iNewHeight);
                }
                else if ($this->getNewHeight() >= $imageHeight)
                {
                    $x = ($this->getNewHeight() / $imageHeight);
                    $iNewWidth = $imageWidth * $x;
                    $this->setNewWidth($iNewWidth);
                } // if...else...
            } // if...else...
        } // if

        $imageType = substr(strrchr($imageMime, '/'), 1);

        switch ($imageType)
        {
            case 'jpeg':
                $imageCreateFrom = 'ImageCreateFromJPEG';
                $imageSaveTo = 'ImageJPEG';
                $newImageExtension = 'jpg';
                break;

            case 'png':
                $imageCreateFrom = 'ImageCreateFromPNG';
                $imageSaveTo = 'ImagePNG';
                $newImageExtension = 'png';
                break;

            case 'bmp':
                $imageCreateFrom = 'ImageCreateFromBMP';
                $imageSaveTo = 'ImageBMP';
                $newImageExtension = 'bmp';
                break;

            case 'gif':
                $imageCreateFrom = 'ImageCreateFromGIF';
                $imageSaveTo = 'ImageGIF';
                $newImageExtension = 'gif';
                break;

            case 'vnd.wap.wbmp':
                $imageCreateFrom = 'ImageCreateFromWBMP';
                $imageSaveTo = 'ImageWBMP';
                $newImageExtension = 'bmp';
                break;

            case 'xbm':
                $imageCreateFrom = 'ImageCreateFromXBM';
                $imageSaveTo = 'ImageXBM';
                $newImageExtension = 'xbm';
                break;

            default:
                $imageCreateFrom = 'ImageCreateFromJPEG';
                $imageSaveTo = 'ImageJPEG';
                $newImageExtension = 'jpg';
        } // select..case...

        $imageCreate = ImageCreateTrueColor($this->getNewWidth(), $this->getNewHeight());
        $newImage = $imageCreateFrom($this->getImageToResize());

        ImageCopyResampled($imageCreate, $newImage, 0, 0, 0, 0, $this->getNewWidth(), $this->getNewHeight(), $imageWidth, $imageHeight);

        if ($this->getFolderToSave()) {
            if ($this->getNewImageName()) {
                $newImageName = $this->getNewImageName() . '.' . $newImageExtension;
            } else {
                $newImageName = $this->setNewThumbName(basename($this->getImageToResize())) . '_resized.' . $newImageExtension;
            }

            $fileSavePath = $this->getFolderToSave() . $newImageName;
        } else {
            header("Content-Type: " . $imageMime);
            $imageSaveTo($imageCreate);
            $fileSavePath = '';
        }

        $imageProcess = $imageSaveTo($imageCreate, $fileSavePath);

        return array('result' => $imageProcess, 'new_file_path' => $fileSavePath);
    } // function

} // class