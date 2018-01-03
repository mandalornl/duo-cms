<?php

namespace Duo\SecurityBundle\Entity;

interface RoleInterface
{
	/**
	 * Set name
	 *
	 * @param string $name
	 *
	 * @return RoleInterface
	 */
	public function setName(string $name): RoleInterface;

	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName(): ?string;

	/**
	 * Set role
	 *
	 * @param string $role
	 *
	 * @return RoleInterface
	 */
	public function setRole(string $role): RoleInterface;

	/**
	 * Get role
	 *
	 * @return string
	 */
	public function getRole(): ?string;
}