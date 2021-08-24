<?php


namespace App\Controller\Backend;


use App\Controller\AbstractController;
use Hyperf\Utils\Str;

class FileController extends AbstractController
{
    public function pictureUpload(\Hyperf\Filesystem\FilesystemFactory $factory)
    {
        $fileStorage = $factory->get('local');

        $file = $this->request->file('file');
        $extension = strtolower($file->getExtension()) ?: 'png';
        $filename = time() . '_' . Str::random(10) . '.' . $extension;
        // Write Files
        $fileStorage->write($filename, file_get_contents($file->getRealPath()));
        $url = 'http://localhost:9553/' . $filename;
        return $this->response->apiSuccess(['url' => $url]);
    }
}
