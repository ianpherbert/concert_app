<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FileUploader
{

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    public function uploadFile(UploadedFile $file)
    {
        $filename = uniqid() . '.' . $file->guessClientExtension();

        $file->move(
            $this->container->getParameter('uploads_dir'),
            $filename
        );

        return $filename;
    }
}
