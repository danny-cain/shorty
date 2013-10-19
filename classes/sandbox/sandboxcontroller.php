<?php

namespace Sandbox;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\JSONView;
use CannyDain\Shorty\Controllers\ShortyController;
use Sandbox\Views\FramedFileManager;
use Sandbox\Views\RichTextView;
use Sandbox\Views\SandboxView;

class SandboxController extends ShortyController
{
    public function Index()
    {
        $view = new SandboxView();

        $view->setLinks(array
        (
            '/sandbox-sandboxcontroller/richtext' => 'Rich Text',
            '/sandbox-sandboxcontroller/filemanagerframe' => 'File Manager'
        ));

        return $view;
    }

    public function FileManagerFrame()
    {
        $view = new FramedFileManager();
        $view->setFileManagerRoute(new Route(FileManagerAPI::CONTROLLER_NAME, 'ls'));

        return $view;
    }

    public function RichText()
    {
        return new RichTextView();
    }
}