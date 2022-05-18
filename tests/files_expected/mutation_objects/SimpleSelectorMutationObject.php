<?php

namespace GraphQL\Tests\SchemaObject;

class SimpleSelectorMutationObject extends \GraphQL\SchemaObject\MutationObject
{
    public const OBJECT_NAME = 'SimpleSelector';

    public function selectName()
    {
        $this->selectField('name');
        return $this;
    }
}
