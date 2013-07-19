<?php

namespace CannyDain\ShortyCoreModules\URIManager\Views;

use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\FormHelperConsumer;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\Shorty\UI\ViewHelpers\FormHelper;
use CannyDain\ShortyCoreModules\URIManager\Models\URIMappingModel;

class EditURIView extends HTMLView implements FormHelperConsumer, RouterConsumer
{
    /**
     * @var URIMappingModel
     */
    protected $_uri;

    /**
     * @var Route
     */
    protected $_searchControllersAPIRoute;
    /**
     * @var Route
     */
    protected $_searchMethodsAPIRoute;
    /**
     * @var Route
     */
    protected $_listParamsAPIRoute;

    /**
     * @var RouterInterface
     */
    protected $_router;

    /**
     * @var FormHelper
     */
    protected $_formHelper;

    protected $_saveURI;

    public function updateFromRequest(Request $request)
    {
        $params = $request->getParameter('params');
        if (!is_array($params))
            $params = array();

        $this->_uri->setUri($request->getParameter('uri'));
        $this->_uri->setController($request->getParameter('controller'));
        $this->_uri->setMethod($request->getParameter('method'));
        $this->_uri->setParams($params);
    }

    public function display()
    {
        echo '<h1>Edit URI</h1>';

        $this->_formHelper->startForm($this->_saveURI);
            $this->_formHelper->editText('uri', 'URI', $this->_uri->getUri(), 'The URI you wish to map (must NOT start with a /)');
            $this->_formHelper->editTextWithoutAutocomplete('controller', 'Controller', $this->_uri->getController(), 'The controller to map this URI to');
            $this->_formHelper->editTextWithoutAutocomplete('method', 'Method', $this->_uri->getMethod(), 'The method to map this URI to');
            echo '<div id="paramInfo"></div>';
            $this->_formHelper->editText('param', 'Params', '', 'The parameters to map this URI to');
            echo '<div id="paramList">';
                foreach ($this->_uri->getParams() as $param)
                {
                    echo '<div>';
                        $this->_formHelper->hiddenField('params[]', $param);
                        echo $param;
                        echo ' <a href="" onclick="$(this).parent().remove(); return false;">[delete]</a>';
                    echo '</div>';
                }
            echo '</div>';

            $this->_formHelper->submitButton('Save');
        $this->_formHelper->endForm();

        $controllerLookupURI = $this->_router->getURI($this->_searchControllersAPIRoute);
        $methodLookupURI = $this->_router->getURI($this->_searchMethodsAPIRoute);
        $paramLookupURI = $this->_router->getURI($this->_listParamsAPIRoute);

        echo <<<HTML
<script type="text/javascript">
    function Autocomplete(uriGeneratorCallback, extraParamsCallback, targetElement, resultParserCallback)
    {
        targetElement.data('autocomplete',
        {
            element : targetElement,
            uriCallback : uriGeneratorCallback,
            paramsCallback : extraParamsCallback,
            parseResult : resultParserCallback,
            currentRequest : null,
            results : $(),
            initialise : function()
            {
                var self = this;

                this.results = $('<div style="position: absolute;" class="autocompleteResults"></div>');
                this.results.hide();
                $('body').append(this.results);

                this.element.keypress(function(e)
                {
                    self.abort();
                    var ele = $(this);
                    var val = ele.val();

                    if (val.length < 3)
                        return;

                    self.doAutocomplete(val);
                });
            },
            displayResults : function(data)
            {
                this.results.empty();
                for (var i in data)
                {
                    if (!data.hasOwnProperty(i))
                        continue;

                    var element = this.parseResult(data[i]);
                    if (element == null)
                        continue;

                    this.results.append(element);
                }

                var position = this.element.position();

                position.top = position.top + this.element.height();

                this.results.css('left', position.left);
                this.results.css('top', position.top);
                this.results.show();
            },
            doAutocomplete : function(text)
            {
                var self = this;

                this.abort();
                var params = this.paramsCallback(text);
                var uri = this.uriCallback(text);

                this.currentRequest = $.get(uri, params, function(data)
                {
                    if (typeof data != 'object')
                        data = eval("(" + data + ")");

                    self.displayResults(data);
                });
            },
            abort : function()
            {
                if (this.currentRequest != null)
                    this.currentRequest.abort();

                this.currentRequest = null;
                this.results.hide();
            }
        });

        targetElement.data('autocomplete').initialise();
        return targetElement.data('autocomplete');
    }

    function updateParamInfo()
    {
        var container = $('#paramInfo');
        var uri = "$paramLookupURI";

        if (container.data('jqAJAX') != undefined && container.data('jqAJAX') !== null)
        {
            container.data('jqAJAX').abort();
            container.data('jqAJAX', null);
        }
        container.empty();

        container.data('jqAJAX', $.get(uri,
        {
            controller : $('[name=controller]').val(),
            method : $('[name=method]').val()
        }, function(data)
        {
            if (typeof data != 'object')
                data = eval("(" + data + ")");

            for (var i in data)
            {
                if (!data.hasOwnProperty(i))
                    continue;

                var element = $('<div class="row"></div>');
                var text = data[i].name;

                if (data[i].required)
                    text = text + ' REQUIRED';
                else
                    text = text + ' OPTIONAL';

                element.text(text);
                container.append(element);
            }
        }));
    }

    $(document).ready(function()
    {
        window.controllerAutocomplete = Autocomplete(function(text) // uri creation
        {
            return "{$controllerLookupURI}";
        }, function(text) // parameters creation
        {
            return { query : text };
        }, $('[name=controller]'), function(row)
        {
            var id = row;
            var parts = row.split("\\\\");
            var name = parts.pop();

            var element = $('<div data-id="' + id + '" class="result">' + name + '</div>');

            element.click(function(e)
            {
                $('[name=controller]').val($(this).attr('data-id'));
                window.controllerAutocomplete.abort();
                e.stopPropagation();
            });

            return element;
        });

        window.methodAutocomplete = Autocomplete(function(text) // uri creation
        {
            return "{$methodLookupURI}";
        }, function(text) // parameters creation
        {
            return { query : text, controller : $('[name=controller]').val() };
        }, $('[name=method]'), function(row)
        {
            var element = $('<div class="result">' + row + '</div>');

            element.click(function(e)
            {
                $('[name=method]').val($(this).text());
                window.methodAutocomplete.abort();
                updateParamInfo();

                e.stopPropagation();
            });

            return element;
        });

        $('body').click(function()
        {
            window.methodAutocomplete.abort();
            window.controllerAutocomplete.abort();
        });

        $('[name=method],[name=controller]').keypress(function()
        {
            $('#paramInfo').empty();
        });

        $('[name=param]').keypress(function(e)
        {
            if (e.which != 13)
                return true;

            var input = $(this);
            var param = input.val();
            input.val('');

            param = param.replace('"', '&quot;', 'g');
            var newRow = $('<div></div>');
            newRow.append('<input type="hidden" name="params[]" value="' + param + '" />');
            newRow.append(param);
            newRow.append(' <a href="" onclick="$(this).parent().remove(); return false;">[delete]</a>');

            $('#paramList').append(newRow);
            return false;
        });
    });
</script>
HTML;

    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $listParamsAPIRoute
     */
    public function setListParamsAPIRoute($listParamsAPIRoute)
    {
        $this->_listParamsAPIRoute = $listParamsAPIRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getListParamsAPIRoute()
    {
        return $this->_listParamsAPIRoute;
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $searchControllersAPIRoute
     */
    public function setSearchControllersAPIRoute($searchControllersAPIRoute)
    {
        $this->_searchControllersAPIRoute = $searchControllersAPIRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getSearchControllersAPIRoute()
    {
        return $this->_searchControllersAPIRoute;
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $searchMethodsAPIRoute
     */
    public function setSearchMethodsAPIRoute($searchMethodsAPIRoute)
    {
        $this->_searchMethodsAPIRoute = $searchMethodsAPIRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getSearchMethodsAPIRoute()
    {
        return $this->_searchMethodsAPIRoute;
    }

    public function setSaveURI($saveURI)
    {
        $this->_saveURI = $saveURI;
    }

    public function getSaveURI()
    {
        return $this->_saveURI;
    }

    /**
     * @param \CannyDain\ShortyCoreModules\URIManager\Models\URIMappingModel $uri
     */
    public function setUri($uri)
    {
        $this->_uri = $uri;
    }

    /**
     * @return \CannyDain\ShortyCoreModules\URIManager\Models\URIMappingModel
     */
    public function getUri()
    {
        return $this->_uri;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeFormHelper(FormHelper $dependency)
    {
        $this->_formHelper = $dependency;
    }

    public function consumeRouter(RouterInterface $dependency)
    {
        $this->_router = $dependency;
    }
}