<?php

namespace ApplicationTest\Validator;

use PHPUnit_Framework_TestCase;
use Webfactory\Doctrine\ORMTestInfrastructure\ORMInfrastructure;
use Application\Entity\Sample as SampleEntity;
use Application\Validator\EntityNotExists;

class EntityNotExistsTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->infrastructure = new ORMInfrastructure([
            '\Application\Entity\Sample',
        ]);
        $this->repository = $this->infrastructure->getRepository('Application\Entity\Sample');
        $this->em = $this->infrastructure->getEntityManager();
    }

    public function testOnExistingEntity()
    {
        $a = new SampleEntity($em);
        $a->setValueString('foo');

        $this->infrastructure->import([ $a ]);

        $validator = new EntityNotExists([
            'entityManager' => $this->em,
            'entity'        => 'Application\Entity\Sample',
            'property'      => 'value_string',
        ]);

        $result = $validator->isValid('foo');
        $this->assertEquals(false, $result, "Check existing entity");

        $validator = new EntityNotExists([
            'entityManager' => $this->em,
            'entity'        => 'Application\Entity\Sample',
            'property'      => 'value_string',
            'alwaysValidId' => 1
        ]);

        $result = $validator->isValid('foo');
        $this->assertEquals(true, $result, "Check always valid entity");
    }

    public function testOnNonExistingEntity()
    {
        $validator = new EntityNotExists([
            'entityManager' => $this->em,
            'entity'        => 'Application\Entity\Sample',
            'property'      => 'value_string',
        ]);

        $result = $validator->isValid('foo');
        $this->assertEquals(true, $result, "Check non-existing entity");
    }
}
