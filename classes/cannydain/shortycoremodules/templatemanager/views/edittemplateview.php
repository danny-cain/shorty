<?php

namespace CannyDain\ShortyCoreModules\TemplateManager\Views;

use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Response\TemplatedDocuments\Models\Template;
use CannyDain\Lib\UI\Response\TemplatedDocuments\Models\TemplatedDocumentElement;
use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\InstanceManagerConsumer;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\Shorty\InstanceManager\InstanceManager;
use CannyDain\ShortyCoreModules\TemplateManager\TemplateManagerModule;

class EditTemplateView extends HTMLView implements InstanceManagerConsumer, RouterConsumer
{
    /**
     * @var InstanceManager
     */
    protected $_instanceManager;

    /**
     * @var string
     */
    protected $_template = '';

    /**
     * @var RouterInterface
     */
    protected $_router;

    protected $_templateName = '';
    /**
     * @var Route
     */
    protected $_saveRoute;

    public function updateFromRequest(Request $request)
    {
        $this->_template = $request->getParameter('template');
        $this->_templateName = $request->getParameter('name');
    }

    public function display()
    {
        echo '<div id="templateEditor">';

        echo '</div>';

        $saveURI = $this->_router->getURI($this->_saveRoute);
        echo '<form method="post" action="'.$saveURI.'" id="editTemplateForm">';
            echo '<input type="hidden" name="template" value="" />';
            echo '<input type="text" name="name" value="'.$this->_templateName.'" />';
            echo '<input type="submit" value="Save Template" />';
        echo '</form>';

        $this->_includeScripts();
        $this->_writeInitialisation();
    }

    protected function _writeInitialisation()
    {
        $template = json_decode($this->_template, true);
        $types = $this->_getElementTypeInitialisation('editor');
        $templateInitialisation = $this->_getTemplateInitialisation($template, 'editor');

        echo <<<HTML
        <script type="text/javascript">
        $(document).ready(function()
        {
            var editor = new TemplateEditor();
            $('#templateEditor').data('editor', editor);

            $types
            $templateInitialisation

            var view = new TemplateEditorView(editor,
            {
                "toolboxID" : "te_Toolbox",
                "editorID" : "te_Editor",
                "pathID" : "te_Path",
                "container" : "#templateEditor"
            });

            $('#templateEditor').data('editorView', view);

            $('#editTemplateForm').submit(function()
            {
                var json = editor.getTemplateAsJSON();
                $(this).find('[name="template"]').val(json);
            });

            $('#getJSON').click(function()
            {
                $('#json').text(editor.getTemplateAsJSON());
            });
        });
        </script>
HTML;
    }

    protected function _getTemplateInitialisation($template, $editorInstanceName)
    {
        $lines = array();

        foreach ($template as $node)
        {
            $class = '"'.strtr($node['class'], array('\\' => '\\\\')).'"';
            $children = $this->_getTemplateElementInitialisationArray($node['children']);
            $params = json_encode($node['params']);

            $constructor = 'new TemplateElement('.$class.', '.$params.', '.$children.')';
            $lines[] = $editorInstanceName.'.template.elements.push('.$constructor.');';
        }

        return "\t".implode("\r\n\t", $lines)."\r\n";
    }

    protected function _getTemplateElementInitialisationArray($elements)
    {
        $lines = array();
        foreach ($elements as $element)
        {
            $constructorArgs = array
            (
                '"'.strtr($element['class'], array('\\' => '\\\\')).'"',
                json_encode($element['params'])
            );

            if (isset($element['children']))
                $constructorArgs[] = $this->_getTemplateElementInitialisationArray($element['children']);
            else
                $constructorArgs[] = '[]';

            $lines[] = 'new TemplateElement('.implode(', ', $constructorArgs).')';
        }

        return '['.implode(', ', $lines).']';
    }

    /**
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->_template = $template;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->_template;
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $saveRoute
     */
    public function setSaveRoute($saveRoute)
    {
        $this->_saveRoute = $saveRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getSaveRoute()
    {
        return $this->_saveRoute;
    }

    public function setTemplateName($templateName)
    {
        $this->_templateName = $templateName;
    }

    public function getTemplateName()
    {
        return $this->_templateName;
    }

    protected function _getElementTypeInitialisation($editorInstanceName)
    {
        $lines = array();
        $typeID = $this->_instanceManager->getTypeByInterfaceOrClassname(TemplateManagerModule::ELEMENT_TYPE_NAME)->getId();
        foreach ($this->_instanceManager->getInstancesByType($typeID) as $type)
        {
            $class = $type->getClassName();
            if (!class_exists($class))
                continue;

            $reflectionClass = new \ReflectionClass($class);
            $hasChildren = $reflectionClass->implementsInterface(TemplateManagerModule::CONTAINER_ELEMENT_INTERFACE);
            $nameParts = explode('\\', $class);
            $friendlyName = array_pop($nameParts);

            $paramDefinition = array();
            $constructor = $reflectionClass->getConstructor();

            if ($constructor != null)
            {
                foreach ($reflectionClass->getConstructor()->getParameters() as $param)
                {
                    $paramName = $param->getName();

                    if ($param->isOptional())
                        $paramDefault = $param->getDefaultValue();
                    else
                        $paramDefault = '';

                    if (is_array($paramDefault))
                        $paramType = 'array';
                    else
                        $paramType = 'string';

                    $paramDefinition[$paramName] = $paramType;
                }
            }

            $constructorParams = '"'.strtr($class, array('\\' => '\\\\')).'", "'.$friendlyName.'", '.json_encode($paramDefinition).', '.($hasChildren ? 'true' : 'false');
            $lines[] = $editorInstanceName.'.addElement(new Element('.$constructorParams.'));';
        }

        return "\t".implode("\r\n\t", $lines)."\r\n";
    }

    protected function _includeScripts()
    {
        echo <<<HTML
<script type="text/javascript" src="/scripts/templates/editor.js"></script>
<script type="text/javascript" src="/scripts/templates/editorview.js"></script>
<script type="text/javascript" src="/scripts/templates/models.js"></script>
HTML;

    }

    public function dependenciesConsumed()
    {

    }

    public function consumeInstanceManager(InstanceManager $dependency)
    {
        $this->_instanceManager = $dependency;
    }

    public function consumeRouter(RouterInterface $dependency)
    {
        $this->_router = $dependency;
    }
}