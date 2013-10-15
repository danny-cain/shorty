<?php

namespace Sandbox;

use CannyDain\Lib\UI\Views\JSONView;
use CannyDain\Shorty\Controllers\ShortyController;
use Sandbox\Views\FileManagerUploadView;
use Sandbox\Views\FileManagerView;
use Sandbox\Views\RichTextView;
use Sandbox\Views\SandboxView;

class SandboxController extends ShortyController
{
    public function Index()
    {
        $view = new SandboxView();

        $view->setLinks(array
        (
            '/sandbox-sandboxcontroller/pdf' => 'PDF',
            '/sandbox-sandboxcontroller/richtext' => 'Rich Text',
            '/sandbox-sandboxcontroller/filemanager' => 'File Manager Test',
        ));

        return $view;
    }

    public function FileManagerUpload()
    {
        $view = new FileManagerUploadView();

        $view->setStatus("Ok");
        $view->setCallbackFunction($this->_request->getParameter('callback'));
        $view->setCallbackID($this->_request->getParameter('callbackID'));

        return $view;
    }

    public function FileManagerAPI_List()
    {
        $path = $this->_request->getParameter('path');
        $root = dirname(dirname(dirname(__FILE__))).'/';

        $files = array();
        $directories = array();

        $path = strtr($path, array('..' => ''));
        $dir = opendir($root.$path);
        while ($entry = readdir($dir))
        {
            if ($entry == '.' || $entry == '..')
                continue;

            $fullPath = $root.$path.$entry;
            if (is_dir($fullPath))
                $directories[] = array('name' => $entry.'/', 'path' => $path, 'type' => 'D', 'mimeType' => '');
            else
                $files[] = array('name' => $entry, 'path' => $path, 'type' => 'F', 'mimeType' => 'application/unknown');
        }

        return new JSONView(array('directories' => $directories, 'files' => $files));
    }

    public function FileManagerAPI_Delete()
    {

    }

    public function FileManager()
    {
        $view = new FileManagerView();

        return $view;
    }

    public function RichText()
    {
        return new RichTextView();
    }
    /*
    public function PDF()
    {
        $pdf = new PDFExtension();

        $template = DisplayElement::getTestTemplate();
        $template->display($pdf);

        $view = new PDFView();
        $view->setPdf($pdf);

        return $view;
    }
    */
}