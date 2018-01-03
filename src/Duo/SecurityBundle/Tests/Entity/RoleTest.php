<?php

namespace Duo\SecurityBundle\Tests\Entity;

use Duo\SecurityBundle\Entity\Role;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
	/**
	 * @var Role
	 */
	private $entity;

	/**
	 * {@inheritdoc}
	 */
	protected function setUp()
	{
		$this->entity = (new Role())
			->setName('Test role')
			->setRole('ROLE_TEST');
	}

	/**
	 * @covers Role::getId()
	 */
	public function testGetId()
	{
		$this->assertEquals(null, $this->entity->getId());
	}

	/**
	 * @covers Role::__toString()
	 */
	public function test__toString()
	{
		$this->assertEquals('Test role', (string)$this->entity);
	}

	/**
	 * @covers Role::setName()
	 * @covers Role::getName()
	 */
	public function testSetAndGetName()
	{
		$this->entity->setName('Test foobar');

		$this->assertEquals('Test foobar', $this->entity->getName());
	}

	/**
	 * @covers Role::setRole()
	 * @covers Role::getRole()
	 */
	public function testSetAndGetRole()
	{
		$this->entity->setRole('ROLE_FOOBAR');

		$this->assertEquals('ROLE_FOOBAR', $this->entity->getRole());
	}
}