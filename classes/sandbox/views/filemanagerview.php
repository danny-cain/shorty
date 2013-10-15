<?php

namespace Sandbox\Views;

use CannyDain\Shorty\Views\ShortyView;

class FileManagerView extends ShortyView
{
    public function display()
    {
        echo <<<HTML
        <button id="fileManager">File Manager</button>
<script type="text/javascript">
    $(document).ready(function()
    {
        var api = new window.shorty.apiClients.FileManager(
        {
            "list" : "/sandbox-sandboxcontroller/filemanagerapi_list",
            "delete" : "/sandbox-sandboxcontroller/filemanagerapi_delete",
            "upload" : "/sandbox-sandboxcontroller/filemanagerupload"
        });

        var view = new window.shorty.views.FileManagerView(
        {
            client : api
        });

        $('#fileManager').on('click', function()
        {
            view.display("/");
            return false;
        });
    });
</script>
HTML;

    }
}