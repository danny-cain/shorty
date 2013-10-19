<?php

namespace Sandbox\Views;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Consumers\FileManagerConsumer;
use CannyDain\Shorty\FileManager\FileManagerInterface;
use CannyDain\Shorty\Views\ShortyView;

class FramedFileManager extends ShortyView implements FileManagerConsumer
{
    /**
     * @var Route
     */
    protected $_fileManagerRoute;

    /**
     * @var FileManagerInterface
     */
    protected $_fileManager;

    public function display()
    {
        $uri = $this->_router->getURI($this->_fileManagerRoute);

        $route = $this->_fileManager->getFileManagerFrameRoute('/', array('select' => 'Select'));
        $newURI = $this->_router->getURI($route);
        echo '<iframe style="width: 100%; border: none; height: 300px; " id="filemanager" src="'.$newURI.'"></iframe>';

        echo <<<HTML
<script type="text/javascript">
    $(document).ready(function()
    {
        window.addEventListener("message", function(e)
        {
            var origin = window.location.protocol + "//" + window.location.host;

            if (origin != e.origin)
            {
                console.log("Discarding origin:" + e.origin + "(" + origin + ")");
                return;
            }

            var data = e.data;

            switch(data.message)
            {
                case 'select':
                    console.log("Selected " + data.file.path + data.file.name + " (" + data.file.webPath + ")");
                    break;
                case 'delete':
                    console.log("Deleted " + data.file.path + data.file.name + " (" + data.file.webPath + ")");
                    break;
            }
        });
    });
</script>
HTML;

    }

    public function consumeFileManager(FileManagerInterface $fileManager)
    {
        $this->_fileManager = $fileManager;
    }


    /**
     * @param \CannyDain\Lib\Routing\Models\Route $fileManagerRoute
     */
    public function setFileManagerRoute($fileManagerRoute)
    {
        $this->_fileManagerRoute = $fileManagerRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getFileManagerRoute()
    {
        return $this->_fileManagerRoute;
    }
}