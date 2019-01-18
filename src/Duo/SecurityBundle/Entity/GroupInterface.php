<?php

namespace Duo\SecurityBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Duo\CoreBundle\Entity\Property\IdInterface;
use Duo\CoreBundle\Entity\Property\TimestampInterface;

interface GroupInterface extends IdInterface, TimestampInterface
{
	/**
	 * Set name
	 *
	 * @param string $name
	 *
	 * @return GroupInterface
	 */
	public function setName(string $name): GroupInterface;

	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName(): ?string;

	/**
	 * Add role
	 *
	 * @param RoleInterface $role
	 *
	 * @return GroupInterface
	 */
	public function addRole(RoleInterface $role): GroupInterface;

	/**
	 * Remove role
	 *
	 * @param RoleInterface $role
	 *
	 * @return GroupInterface
	 */
	public function removeRole(RoleInterface $role): GroupInterface;

	/**
	 * Get roles
	 *
	 * @return ArrayCollection
	 */
	public function getRoles(): Collection;

	/**
	 * Get roles flattened
	 *
	 * @return array
	 */
	public function getRolesFlattened(): array;
}
