<?php

namespace App\Utils;

use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\Asset\Context\RequestStackContext;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderHelper
{
    const BOOK_IMAGE = 'book_image';
    private $uploadsPath;
    /**
     * @var RequestStackContext
     */
    private $requestStackContext;

    /**
     * UploadHelper constructor.
     *
     * @param $uploadsPath
     * @param RequestStackContext $requestStackContext
     */
    public function __construct($uploadsPath, RequestStackContext $requestStackContext)
    {
        $this->uploadsPath = $uploadsPath;
        $this->requestStackContext = $requestStackContext;
    }

    public function uploadBookImage(File $file): string
    {
        $destination = $this->uploadsPath.'/'.self::BOOK_IMAGE;

        if ($file instanceof UploadedFile) {
            $originalFilename = $file->getClientOriginalName();
        } else {
            $originalFilename = $file->getFilename();
        }
        $newFileName = Urlizer::urlize(pathinfo($originalFilename, PATHINFO_FILENAME)).'-'.uniqid().'.'.$file->guessExtension();
        $file->move($destination, $newFileName);

        return $newFileName;
    }

    public function getPublicPath(string $path): string
    {
        //need requestStackContext to show correct dir when have subdir instead homepage /
        return $this->requestStackContext->getBasePath().'/uploads/'.$path;
    }
}
