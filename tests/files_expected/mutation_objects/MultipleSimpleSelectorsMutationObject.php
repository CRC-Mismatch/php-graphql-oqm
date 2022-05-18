<?php

namespace GraphQL\Tests\SchemaObject;

class MultipleSimpleSelectorsMutationObject extends \GraphQL\SchemaObject\MutationObject
{
    public const OBJECT_NAME = 'MultipleSimpleSelectors';

    public function selectFirstName()
    {
        $this->selectField('first_name');
        return $this;
    }

    /**
     * @deprecated is deprecated
     */
    public function selectLastName()
    {
        $this->selectField('last_name');
        return $this;
    }

    public function selectGender()
    {
        $this->selectField('gender');
        return $this;
    }
}
