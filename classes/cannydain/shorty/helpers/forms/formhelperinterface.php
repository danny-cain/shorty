<?php

namespace CannyDain\Shorty\Helpers\Forms;

use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Helpers\Forms\Models\FieldModel;

interface FormHelperInterface
{
    const FORM_METHOD_GET = 'GET';
    const FORM_METHOD_POST = 'POST';

    /**
     * @param string $method
     * @return FormHelperInterface
     */
    public function setMethod($method = self::FORM_METHOD_GET);

    /**
     * @param $uri
     * @return FormHelperInterface
     */
    public function setURI($uri);

    /**
     * @param FieldModel $field
     * @return FormHelperInterface
     */
    public function addField(FieldModel $field);

    /**
     * @return FormHelperInterface
     */
    public function displayForm();

    /**
     * @return FieldModel[]
     */
    public function getAllFields();

    /**
     * @param $fieldName
     * @return FieldModel
     */
    public function getField($fieldName);

    /**
     * @param Request $request
     * @return FormHelperInterface
     */
    public function updateFromRequest(Request $request);

}