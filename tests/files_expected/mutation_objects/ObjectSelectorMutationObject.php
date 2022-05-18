<?php

namespace GraphQL\Tests\SchemaObject;

class ObjectSelectorMutationObject extends \GraphQL\SchemaObject\MutationObject
{
    public const OBJECT_NAME = 'ObjectSelector';

    public function selectOthers(\RootOthersArgumentsObject $argsObject = null)
    {
        $object = new OtherQueryObject('others');
        if ($argsObject !== null) {
            $object->appendArguments($argsObject->toArray());
        }
        $this->selectField($object);
        return $object;
    }
}
