<?php

namespace Duo\SecurityBundle\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Duo\SecurityBundle\Entity\Group;
use Duo\SecurityBundle\Entity\Role;
use Duo\SecurityBundle\Entity\RoleInterface;
use PHPUnit\Framework\TestCase;

class GroupTest extends TestCase
{
	/**
	 * @var Group
	 */
	private $entity;

	/**
	 * {@inheritdoc}
	 */
	protected function setUp()
	{
		$this->entity = (new Group())
			->setName('Test group');
	}

	/**
	 * @covers Group::getId()
	 */
	public function testGetId()
	{
		$this->assertEquals(null, $this->entity->getId());
	}

	/**
	 * @covers Group::__toString()
	 */
	public function test__toString()
	{
		$this->assertEquals('Test group', (string)$this->entity);
	}

	/**
	 * @covers Group::getRoles()
	 */
	public function testGetRoles()
	{
		$role = $this->getRole();
		$this->entity->addRole($role);

		$collection = new ArrayCollection();
		$collection->add($role);

		$this->assertEquals($collection, $this->entity->getRoles());
	}

	/**
	 * @covers Group::getRoles(true)
	 */
	public function testGetRolesFlattened()
	{
		$role = $this->getRole();
		$this->entity->addRole($role);

		$roles = $this->entity->getRoles(true);
		$this->assertEquals(['ROLE_TEST'], $roles);
		$this->assertNotEquals(['ROLE_FOOBAR'], $roles);
	}

	/**
	 * @covers Group::addRole()
	 * @covers Group::removeRole()
	 */
	public function testAddAndRemoveRole()
	{
		$role = $this->getRole();
		$this->entity->addRole($role);

		$this->assertEquals(['ROLE_TEST'], $this->entity->getRoles(true));

		$this->entity->removeRole($role);
		$this->assertNotEquals(['ROLE_TEST'], $this->entity->getRoles(true));
	}

	/**
	 * Get role
	 *
	 * @param string $name [optional]
	 * @param string $roleName [optional]
	 *
	 * @return RoleInterface
	 */
	private function getRole($name = 'Test role', $roleName = 'ROLE_TEST')
	{
		$role = $this->getMockBuilder(Role::class)->getMock();

		$role->expects($this->any())
			->method('getName')
			->willReturn($name);

		$role->expects($this->any())
			->method('getRole')
			->willReturn($roleName);

		/**
		 * @var RoleInterface $role
		 */
		return $role;
	}
}