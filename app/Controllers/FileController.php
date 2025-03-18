<?php
namespace App\Controllers;
use CodeIgniter\Controller;

class FileController extends Controller
{
    public function serveMessage($filename)
    {
        $path = FCPATH . 'uploads/messages/' . $filename;

        if (!file_exists($path)) {
            return $this->response->setStatusCode(404, 'File Not Found');
        }

        return $this->response->setHeader('Content-Type', mime_content_type($path))
                              ->setBody(file_get_contents($path));
    }
}
