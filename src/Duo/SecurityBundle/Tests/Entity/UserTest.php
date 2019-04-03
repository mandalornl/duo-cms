<?php

namespace Duo\SecurityBundle\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Duo\SecurityBundle\Entity\Group;
use Duo\SecurityBundle\Entity\GroupInterface;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
	//  TODO: rewrite
//	/**
//	 * @var AbstractUser
//	 */
//	private $entity;
//
//	/**
//	 * {@inheritDoc}
//	 */
//	protected function setUp()
//	{
//
//		$this->entity = (new AbstractUser())
//			->setName('John Doe')
//			->setUsername('admin');
//
//		$this->entity->addGroup($this->getGroup());
//	}
//
//	/**
//	 * @covers AbstractUser::getId()
//	 */
//	public function testGetId()
//	{
//		$this->assertEquals(null, $this->entity->getId());
//	}
//
//	/**
//	 * @covers AbstractUser::__toString()
//	 */
//	public function test__toString()
//	{
//		$this->assertEquals('admin', (string)$this->entity);
//	}
//
//	/**
//	 * @covers AbstractUser::getGroups()
//	 */
//	public function testGetGroupsAndCount()
//	{
//		$collection = new ArrayCollection();
//		$collection->add($this->getGroup());
//
//		$this->assertEquals($collection, $this->entity->getGroups());
//
//		$this->assertCount(1, $this->entity->getGroups()->toArray());
//		$this->assertNotCount(10, $this->entity->getGroups()->toArray());
//	}
//
//	/**
//	 * @covers AbstractUser::hasRole()
//	 * @covers AbstractUser::hasRoles()
//	 */
//	public function testHasRole()
//	{
//		$this->assertTrue($this->entity->hasRole('ROLE_ADMIN'));
//		$this->assertFalse($this->entity->hasRole('ROLE_TEST'));
//
//		$this->assertTrue($this->entity->hasRoles(['ROLE_SUPER_ADMIN']));
//		$this->assertFalse($this->entity->hasRoles(['ROLE_TEST']));
//	}
//
//	/**
//	 * Get group
//	 *
//	 * @param string $name [optional]
//	 *
//	 * @return GroupInterface
//	 */
//	private function getGroup(string $name = 'Test group')
//	{
//		$group = $this->getMockBuilder(Group::class)
//			->getMock();
//
//		$group
//			->expects($this->any())
//			->method('getName')
//			->willReturn($name);
//
//		$group
//			->expects($this->any())
//			->method('getRoles')
//			->willReturn([
//				'ROLE_SUPER_ADMIN',
//				'ROLE_ADMIN'
//			]);
//
//		/**
//		 * @var GroupInterface $group
//		 */
//		return $group;
//	}
}
