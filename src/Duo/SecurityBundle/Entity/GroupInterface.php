<?php

namespace Duo\SecurityBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

interface GroupInterface
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
	 * @param bool $flatten [optional]
	 *
	 * @return ArrayCollection|array
	 */
	public function getRoles(bool $flatten = false);
}