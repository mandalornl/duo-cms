<?php

namespace Duo\SecurityBundle\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Duo\SecurityBundle\Entity\Group;
use Duo\SecurityBundle\Entity\GroupInterface;
use Duo\SecurityBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
	/**
	 * @var User
	 */
	private $entity;

	/**
	 * {@inheritdoc}
	 */
	protected function setUp()
	{
		$this->entity = (new User())
			->setName('John Doe')
			->setUsername('admin');

		$this->entity->addGroup($this->getGroup());
	}

	/**
	 * @covers User::getId()
	 */
	public function testGetId()
	{
		$this->assertEquals(null, $this->entity->getId());
	}

	/**
	 * @covers User::__toString()
	 */
	public function test__toString()
	{
		$this->assertEquals('admin', (string)$this->entity);
	}

	/**
	 * @covers User::getGroups()
	 */
	public function testGetGroupsAndCount()
	{
		$collection = new ArrayCollection();
		$collection->add($this->getGroup());

		$this->assertEquals($collection, $this->entity->getGroups());

		$this->assertEquals(1, $this->entity->getGroups()->count());
		$this->assertNotEquals(10, $this->entity->getGroups()->count());
	}

	/**
	 * @covers User::hasRole()
	 * @covers User::hasRoles()
	 */
	public function testHasRole()
	{
		$this->assertTrue($this->entity->hasRole('ROLE_ADMIN'));
		$this->assertFalse($this->entity->hasRole('ROLE_TEST'));

		$this->assertTrue($this->entity->hasRoles(['ROLE_SUPER_ADMIN']));
		$this->assertFalse($this->entity->hasRoles(['ROLE_TEST']));
	}

	/**
	 * Get group
	 *
	 * @param string $name [optional]
	 *
	 * @return GroupInterface
	 */
	private function getGroup(string $name = 'Test group')
	{
		$group = $this->getMockBuilder(Group::class)
			->getMock();

		$group
			->expects($this->any())
			->method('getName')
			->willReturn($name);

		$group
			->expects($this->any())
			->method('getRoles')
			->willReturn([
				'ROLE_SUPER_ADMIN',
				'ROLE_ADMIN'
			]);

		/**
		 * @var GroupInterface $group
		 */
		return $group;
	}
}