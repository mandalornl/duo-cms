<?php

namespace Duo\SecurityBundle\Entity;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;

interface UserInterface extends AdvancedUserInterface
{
	/**
	 * Set username
	 *
	 * @param string $username
	 *
	 * @return UserInterface
	 */
	public function setUsername(string $username): UserInterface;

	/**
	 * Get username
	 *
	 * @return string
	 */
	public function getUsername(): ?string;

	/**
	 * Set password
	 *
	 * @param string $password
	 *
	 * @return UserInterface
	 */
	public function setPassword(string $password): UserInterface;

	/**
	 * Set plainPassword
	 *
	 * @param string $plainPassword
	 *
	 * @return UserInterface
	 */
	public function setPlainPassword(string $plainPassword): UserInterface;

	/**
	 * Get plainPassword
	 *
	 * @return string
	 */
	public function getPlainPassword(): ?string;
};