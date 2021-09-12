<?php


namespace App\Controller\Backend;


use App\Controller\AbstractController;
use Hyperf\Utils\Str;

class FileController extends AbstractController
{
    public function pictureUpload(\Hyperf\Filesystem\FilesystemFactory $factory)
    {
        $fileStorage = $factory->get('oss');

        $file = $this->request->file('file');
        $extension = strtolower($file->getExtension()) ?: 'png';
        $filename = 'jiayue/'.time() . '_' . Str::random(10) . '.' . $extension;
        // Write Files
        $fileStorage->write($filename, file_get_contents($file->getRealPath()));
        $url = sprintf("https://%s.%s/%s",env('OSS_BUCKET', ''),env('OSS_ENDPOINT', ''),$filename);
        return $this->response->apiSuccess(['url' => $url]);
    }
}
