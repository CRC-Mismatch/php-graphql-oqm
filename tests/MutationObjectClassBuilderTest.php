<?php

declare(strict_types=1);

namespace GraphQL\Tests;

use GraphQL\Enumeration\FieldTypeKindEnum;
use GraphQL\SchemaGenerator\CodeGenerator\MutationObjectClassBuilder;
use GraphQL\SchemaObject\MutationObject;

class MutationObjectClassBuilderTest extends CodeFileTestCase
{
    private const TESTING_NAMESPACE = 'GraphQL\\Tests\\SchemaObject';

    /**
     * @return string
     */
    protected static function getExpectedFilesDir()
    {
        return parent::getExpectedFilesDir().'/mutation_objects';
    }

    /**
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\MutationObjectClassBuilder::__construct
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\MutationObjectClassBuilder::build
     */
    public function testBuildEmptyMutationObject()
    {
        $objectName = 'Empty';
        $classBuilder = new MutationObjectClassBuilder(static::getGeneratedFilesDir(), $objectName, static::TESTING_NAMESPACE);
        $objectName .= 'MutationObject';
        $classBuilder->build();

        $this->assertFileEquals(
            static::getExpectedFilesDir()."/$objectName.php",
            static::getGeneratedFilesDir()."/$objectName.php"
        );
    }

    /**
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\MutationObjectClassBuilder::__construct
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\MutationObjectClassBuilder::build
     */
    public function testBuildRootMutationObject()
    {
        $objectName = MutationObject::ROOT_MUTATION_OBJECT_NAME;
        $classBuilder = new MutationObjectClassBuilder(static::getGeneratedFilesDir(), $objectName, static::TESTING_NAMESPACE);
        $objectName .= 'MutationObject';
        $classBuilder->build();

        $this->assertFileEquals(
            static::getExpectedFilesDir()."/$objectName.php",
            static::getGeneratedFilesDir()."/$objectName.php"
        );
    }

    /**
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\MutationObjectClassBuilder::addScalarField
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\MutationObjectClassBuilder::addSimpleSelector
     */
    public function testAddSimpleSelector()
    {
        $objectName = 'SimpleSelector';
        $classBuilder = new MutationObjectClassBuilder(static::getGeneratedFilesDir(), $objectName, static::TESTING_NAMESPACE);
        $objectName .= 'MutationObject';
        $classBuilder->addScalarField('name', false, null);
        $classBuilder->build();

        $this->assertFileEquals(
            static::getExpectedFilesDir()."/$objectName.php",
            static::getGeneratedFilesDir()."/$objectName.php"
        );
    }

    /**
     * @depends testAddSimpleSelector
     *
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\MutationObjectClassBuilder::addScalarField
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\MutationObjectClassBuilder::addSimpleSelector
     */
    public function testAddMultipleSimpleSelectors()
    {
        $objectName = 'MultipleSimpleSelectors';
        $classBuilder = new MutationObjectClassBuilder(static::getGeneratedFilesDir(), $objectName, static::TESTING_NAMESPACE);
        $objectName .= 'MutationObject';
        $classBuilder->addScalarField('first_name', false, null);
        $classBuilder->addScalarField('last_name', true, 'is deprecated');
        $classBuilder->addScalarField('gender', false, null);
        $classBuilder->build();

        $this->assertFileEquals(
            static::getExpectedFilesDir()."/$objectName.php",
            static::getGeneratedFilesDir()."/$objectName.php"
        );
    }

    /**
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\MutationObjectClassBuilder::addObjectField
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\MutationObjectClassBuilder::addObjectSelector
     */
    public function testAddObjectSelector()
    {
        $objectName = 'ObjectSelector';
        $classBuilder = new MutationObjectClassBuilder(static::getGeneratedFilesDir(), $objectName, static::TESTING_NAMESPACE);
        $objectName .= 'MutationObject';
        $classBuilder->addObjectField('others', 'Other', FieldTypeKindEnum::OBJECT, 'RootOthersArgumentsObject', false, null);
        $classBuilder->build();

        $this->assertFileEquals(
            static::getExpectedFilesDir()."/$objectName.php",
            static::getGeneratedFilesDir()."/$objectName.php"
        );
    }

    /**
     * @depends testAddObjectSelector
     *
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\MutationObjectClassBuilder::addObjectField
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\MutationObjectClassBuilder::addObjectSelector
     */
    public function testAddMultipleObjectSelectors()
    {
        $objectName = 'MultipleObjectSelectors';
        $classBuilder = new MutationObjectClassBuilder(static::getGeneratedFilesDir(), $objectName, static::TESTING_NAMESPACE);
        $objectName .= 'MutationObject';
        $classBuilder->addObjectField('right', 'MultipleObjectSelectorsRight', FieldTypeKindEnum::OBJECT, static::TESTING_NAMESPACE . '\\MultipleObjectSelectorsRightArgumentsObject', false, null);
        $classBuilder->addObjectField('left_objects', 'Left', FieldTypeKindEnum::OBJECT, static::TESTING_NAMESPACE . '\\MultipleObjectSelectorsLeftObjectsArgumentsObject', true, null);
        $classBuilder->build();

        $this->assertFileEquals(
            static::getExpectedFilesDir()."/$objectName.php",
            static::getGeneratedFilesDir()."/$objectName.php"
        );
    }
}
