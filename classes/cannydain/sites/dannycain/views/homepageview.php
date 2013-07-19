<?php

namespace CannyDain\Sites\DannyCain\Views;

use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Sites\DannyCain\Centralisation\DCCentral;

class HomepageView extends HTMLView
{
    public function display()
    {
        echo '<div id="homepageMarketing" style=" overflow: hidden; height: 180px; ">';
        echo '</div>';

        echo <<<HTML
<p>
This website is still brand spanking new (it still has plastic sheeting on) so there may be the occasional minor issue as I play with the code, please bear with me, and, if you notice a bug, then let me know :)
</p>
HTML;
        $this->_writeScripts();
    }

    protected function _writeScripts()
    {
        $data = array();
        foreach (DCCentral::Singleton()->getMarketingBoxes() as $title => $content)
        {
            $data[] = array('title' => $title, 'content' => $content);
        }
        $boxes = json_encode($data);

        echo <<<HTML
<script type="text/javascript">

    function SlideyMarketingBoxes(element)
    {
        this.boxes = [];
        this.current = -1;
        this.element = element;
        this.moveUp = false;

        this.initialise = function()
        {
            this.setupBoxes($boxes);
            this.animateBox(0);
        };

        this.animateBox = function(index)
        {
            var self = this;

            if (index >= this.boxes.length)
            {
                setTimeout(function()
                {
                    self.moveUp = false;
                    //self.element.children().hide();
                    //self.animateBox(0);
                }, 1000);

                return;
            }

            var marginTop = this.boxes[index].height() * -1;
            if (this.moveUp)
                marginTop = this.boxes[index].height();

            this.boxes[index].css('marginTop', marginTop);
            this.boxes[index].show();
            this.boxes[index].css('display', 'inline-block');

            this.moveUp = !this.moveUp;
            this.boxes[index].animate({marginTop : 0}, 1000, function()
            {
                self.animateBox(index + 1);
            });
        };

        this.setupBoxes = function(boxes)
        {
            var leftOffset = 14;

            for (var i in boxes)
            {
                if (!boxes.hasOwnProperty(i))
                    continue;

                var container = $('<div style="margin-left: ' + leftOffset + '%; height: 160px; padding: 0.5%; width: 24%; margin-right: 10px; vertical-align: top; display: inline-block; border: 1px solid black; border-radius: 5px 5px 0 0; box-shadow: black 5px 5px; "></div>');
                var title = $('<div style="font-weight: bold; text-decoration: underline; font-size: 1.2em; font-style: italic; font-family: comic sans ms;">' + boxes[i].title + '</div>');
                var content = $('<div>' + boxes[i].content + '</div>');

                container.append(title);
                container.append(content);

                leftOffset = 0;
                container.hide();
                this.element.append(container);
                this.boxes.push(container);
            }
        };
        this.initialise();
    }

    $(document).ready(function()
    {
        var element = $('#homepageMarketing');

        element.data('marketing', new SlideyMarketingBoxes(element));
    });
</script>
HTML;
    }
}