<?php

namespace Sandbox\Views;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Views\ShortyView;

class FileManagerListView extends ShortyView
{
    protected $_path = '';
    protected $_listing = array();

    /**
     * @var Route
     */
    protected $_listDirectoryRoute;
    protected $_fileActions = array();
    protected $_directoryActions = array();

    protected function _displayCDUpEntry()
    {
        $parts = explode('/', $this->_path);
        if (array_pop($parts) == '')
            array_pop($parts);

        $path = implode('/', $parts);

        $entry = array
        (
            'type' => 'dir',
            'path' => $path,
            'webPath' => $path,
            'name' => '..'
        );

        if ($this->_path != '' && $this->_path != '/')
            $this->_displayEntry($entry);
    }

    public function display()
    {
        echo '<h1>'.$this->_path.'</h1>';

        echo '<div class="fileManager">';
            $this->_displayCDUpEntry();
            foreach ($this->_listing as $entry)
                $this->_displayEntry($entry);
        echo '</div>';

        $fileActionStr = json_encode($this->_fileActions);
        $directoryActionStr = json_encode($this->_directoryActions);

        echo '<script type="text/javascript">';
        echo <<<JS
        window.fileManager =
        {
            _fileActions : {$fileActionStr},
            _directoryActions : {$directoryActionStr},
            executeFileAction : function(file, action)
            {
                window.parent.postMessage(
                {
                    message : action,
                    file : file
                }, window.location.href);
            },
            executeDirectoryAction : function(directory, action)
            {
                window.parent.postMessage(
                {
                    message : action,
                    file : directory
                }, window.location.href);
            },
            bindContextMenu : function()
            {
                var self = this;

                $('.file').on("contextmenu", function(e)
                {
                    var menu = [];
                    var file =
                    {
                        name : $(this).attr('data-name'),
                        path : $(this).attr('data-path'),
                        webPath : $(this).attr('data-webpath')
                    };

                    for (var key in self._fileActions)
                    {
                        if (!self._fileActions.hasOwnProperty(key))
                            continue;

                        menu.push(new MenuInfo(self._fileActions[key], function(data)
                        {
                            self.executeFileAction(data.file, data.action);
                        },
                        {
                            "action" : key,
                            "file" : file
                        }))
                    }

                    window.contextMenu.drawMenu(e.pageX, e.pageY, menu);
                    return false;
                });

                $('.directory').on("contextmenu", function(e)
                {
                    var menu = [];
                    var directory =
                    {
                        name : $(this).attr('data-name'),
                        path : $(this).attr('data-path'),
                        webPath : $(this).attr('data-webpath')
                    };

                    for (var key in self._directoryActions)
                    {
                        if (!self._directoryActions.hasOwnProperty(key))
                            continue;

                        menu.push(new MenuInfo(self._directoryActions[key], function(data)
                        {
                            self.executeDirectoryAction(data.directory, data.action);
                        },
                        {
                            "action" : key,
                            "directory" : directory
                        }))
                    }

                    window.contextMenu.drawMenu(e.pageX, e.pageY, menu);
                    return false;
                });
            }
        };

        $(document).ready(function()
        {
            window.fileManager.bindContextMenu();
        });

        // convert this into action (i.e. call file manager
        $('.file').on("click", function()
        {
            var data =
            {
                name : $(this).attr('data-name'),
                path : $(this).attr('data-path'),
                webPath : $(this).attr('data-webpath')
            };

            window.fileManager.executeFileAction(data, "select");
            /*window.parent.postMessage({message: "selectFile", file : data}, window.location.href);*/
        });
JS;
        echo '</script>';
    }

    protected function _displayEntry($entry)
    {
        $classes = array();
        $classes[] = 'directoryChild';

        if ($entry['type'] == 'dir')
            $classes[] = 'directory';
        else
            $classes[] = 'file';

        echo '<div data-webpath="'.$entry['webPath'].'" data-path="'.$entry['path'].'" data-name="'.$entry['name'].'" class="'.implode(' ', $classes).'">';
            if ($entry['type'] == 'dir')
                echo '<a href="'.$this->_router->getURI($this->_listDirectoryRoute).'?dir='.$entry['path'].$entry['name'].'">'.$entry['name'].'</a>';
            else
                echo $entry['name'];
        echo '</div>';
    }

    public function setDirectoryActions($directoryActions)
    {
        $this->_directoryActions = $directoryActions;
    }

    public function getDirectoryActions()
    {
        return $this->_directoryActions;
    }

    public function setFileActions($fileActions)
    {
        $this->_fileActions = $fileActions;
    }

    public function getFileActions()
    {
        return $this->_fileActions;
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $listDirectoryRoute
     */
    public function setListDirectoryRoute($listDirectoryRoute)
    {
        $this->_listDirectoryRoute = $listDirectoryRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getListDirectoryRoute()
    {
        return $this->_listDirectoryRoute;
    }

    public function setListing($listing)
    {
        $this->_listing = $listing;
    }

    public function getListing()
    {
        return $this->_listing;
    }

    public function setPath($path)
    {
        $this->_path = $path;
    }

    public function getPath()
    {
        return $this->_path;
    }
}