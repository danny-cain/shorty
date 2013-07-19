<?php

require dirname(dirname(__FILE__)).'/classes/cannydain/autoloader.php';

use CannyDain\Lib\Forms\Models\InputField;
use CannyDain\Lib\Forms\Models\MultipleInputField;

\CannyDain\Autoloader::Singleton()->RegisterRootPath(dirname(dirname(__FILE__)).'/classes/');
\CannyDain\Autoloader::Singleton()->Register();

$fields = getFields();
$factory = new \CannyDain\Lib\Forms\Factories\HTMLInputFieldViewFactory();
$request = new \CannyDain\Lib\Web\Server\Request();
$request->loadFromHTTPRequest();

if ($request->isPost())
    process($fields, $factory, $request);

display($fields, $factory, $request);

/**
 * @param InputField[] $fields
 * @param \CannyDain\Lib\Forms\Factories\InputFieldViewFactory $factory
 * @param \CannyDain\Lib\Web\Server\Request $request
 */
function display($fields, \CannyDain\Lib\Forms\Factories\InputFieldViewFactory $factory, \CannyDain\Lib\Web\Server\Request $request)
{
    echo '<form method="post" action="form-test.php">';
        foreach ($fields as $field)
            $factory->getView($field)->display();
        echo '<input type="submit" value="Post" />';
    echo '</form>';
}

/**
 * @param InputField[] $fields
 * @param \CannyDain\Lib\Forms\Factories\InputFieldViewFactory $factory
 * @param \CannyDain\Lib\Web\Server\Request $request
 */
function process($fields, \CannyDain\Lib\Forms\Factories\InputFieldViewFactory $factory, \CannyDain\Lib\Web\Server\Request $request)
{
    echo '<div style=" padding: 20px; ">';
        echo '<h1>RAW POST</h1>';
        echo '<pre>'.htmlentities(print_r($request->getParameters(), true), ENT_COMPAT, 'UTF-8').'</pre>';

        foreach ($fields as $field)
        {
            $factory->getView($field)->updateModel($request);
            echo '<h1>'.$field->getName().'</h1>';
            echo '<pre>'.htmlentities(print_r($field->getValue(), true), ENT_COMPAT, 'UTF-8').'</pre>';
        }
    echo '</div>';
}

function getFields()
{
    $ret[] = new InputField('name', 'Name', InputField::TYPE_TEXT);
    $ret[] = new InputField('email', 'Email', InputField::TYPE_EMAIL);
    $ret[]= new MultipleInputField('location', 'Location', MultipleInputField::TYPE_SINGLE_SELECT, array(), array('uk' => 'UK', 'usa' => 'USA', 'france' => 'France', 'germany' => 'Germany', 'bulgaria' => 'Bulgaria', 'russia' => 'Russia'));
    $ret[] = new MultipleInputField('food', 'Food', MultipleInputField::TYPE_MULTI_SELECT, array(), array('cheese' => 'Cheese', 'steak' => 'Steak', 'burger' => 'Burger', 'pizza' => 'Pizza', 'chips' => 'Chips', 'sausage' => 'Sausage', 'tomato' => 'Tomato'));

    return $ret;
}