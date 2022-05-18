<?php

declare(strict_types=1);

namespace GraphQL\Tests;

use GraphQL\SchemaObject\ArgumentsObject;
use GraphQL\SchemaObject\InputObject;
use GraphQL\SchemaObject\MutationObject;
use PHPUnit\Framework\TestCase;

/**
 * Class MutationObjectTest.
 */
class MutationObjectTest extends TestCase
{
    /**
     * @var SimpleMutationObject
     */
    protected $MutationObject;

    public function setUp(): void
    {
        $this->MutationObject = new SimpleMutationObject('simples');
    }

    /**
     * @covers \GraphQL\SchemaObject\MutationObject::__construct
     * @covers \GraphQL\SchemaObject\MutationObject::getQuery
     */
    public function testConstruct()
    {
        $object = new SimpleMutationObject();
        $object->selectScalar();
        $this->assertEquals(
            'mutation {
Simple {
scalar
}
}',
            (string) $object->getQuery());

        $object = new SimpleMutationObject('test');
        $object->selectScalar();
        $this->assertEquals(
            'mutation {
test {
scalar
}
}',
            (string) $object->getQuery());
    }

    /**
     * @covers \GraphQL\SchemaObject\MutationObject::selectField
     * @covers \GraphQL\SchemaObject\MutationObject::getQuery
     */
    public function testSelectFields()
    {
        $this->MutationObject->selectScalar();
        $this->assertEquals(
            'mutation {
simples {
scalar
}
}',
            (string) $this->MutationObject->getQuery()
        );

        $this->MutationObject->selectAnotherScalar();
        $this->assertEquals(
            'mutation {
simples {
scalar
anotherScalar
}
}',
            (string) $this->MutationObject->getQuery()
        );

        $this->MutationObject->selectSiblings()->selectScalar();
        $this->assertEquals(
            'mutation {
simples {
scalar
anotherScalar
siblings {
scalar
}
}
}',
            (string) $this->MutationObject->getQuery()
        );
    }

    /**
     * @covers \GraphQL\SchemaObject\MutationObject::appendArguments
     * @covers \GraphQL\SchemaObject\MutationObject::getQuery
     */
    public function testSelectSubFieldsWithArguments()
    {
        $this->MutationObject->selectSiblings((new MutationSimpleSiblingsArgumentObject())->setFirst(5)->setIds([1, 2]))->selectScalar();
        $this->assertEquals(
            'mutation {
simples {
siblings(first: 5 ids: [1, 2]) {
scalar
}
}
}',
            (string) $this->MutationObject->getQuery()
        );

        $this->setUp();
        $this->MutationObject
            ->selectSiblings(
                (new MutationSimpleSiblingsArgumentObject())
                    ->setObject(
                        (new class() extends InputObject {
                            protected $field;

                            public function setField($field)
                            {
                                $this->field = $field;

                                return $this;
                            }
                        })->setField('something')
                    )
            )
            ->selectScalar();
        $this->assertEquals(
            'mutation {
simples {
siblings(obj: {field: "something"}) {
scalar
}
}
}',
            (string) $this->MutationObject->getQuery()
        );
    }
}

class SimpleMutationObject extends MutationObject
{
    public const OBJECT_NAME = 'Simple';

    public function selectScalar()
    {
        $this->selectField('scalar');

        return $this;
    }

    public function selectAnotherScalar()
    {
        $this->selectField('anotherScalar');

        return $this;
    }

    public function selectSiblings(MutationSimpleSiblingsArgumentObject $argumentObject = null)
    {
        $object = new SimpleMutationObject('siblings');
        if ($argumentObject !== null) {
            $object->appendArguments($argumentObject->toArray());
        }
        $this->selectField($object);

        return $object;
    }
}

class MutationSimpleSiblingsArgumentObject extends ArgumentsObject
{
    protected $first;
    protected $ids;
    protected $obj;

    public function setFirst($first)
    {
        $this->first = $first;

        return $this;
    }

    public function setIds(array $ids)
    {
        $this->ids = $ids;

        return $this;
    }

    public function setObject($obj)
    {
        $this->obj = $obj;

        return $this;
    }
}
