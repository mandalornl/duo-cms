<?php

namespace Duo\SecurityBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

interface UserInterface extends AdvancedUserInterface
{
	/**
	 * Set email
	 *
	 * @param string $email
	 *
	 * @return UserInterface
	 */
	public function setEmail(string $email): UserInterface;

	/***
	 * Get email
	 *
	 * @return string
	 */
	public function getEmail(): ?string;

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

	/**
	 * Set passwordToken
	 *
	 * @param string $passwordToken
	 *
	 * @return UserInterface
	 */
	public function setPasswordToken(string $passwordToken = null): UserInterface;

	/**
	 * Get passwordToken
	 *
	 * @return string
	 */
	public function getPasswordToken(): ?string;

	/**
	 * Set passwordRequestedAt
	 *
	 * @param \DateTime $passwordRequestedAt
	 *
	 * @return UserInterface
	 */
	public function setPasswordRequestedAt(\DateTime $passwordRequestedAt = null): UserInterface;

	/**
	 * Get passwordRequestAt
	 *
	 * @return \DateTime
	 */
	public function getPasswordRequestedAt(): ?\DateTime;

	/**
	 * Add group
	 *
	 * @param GroupInterface $group
	 *
	 * @return UserInterface
	 */
	public function addGroup(GroupInterface $group): UserInterface;

	/**
	 * Remove group
	 *
	 * @param GroupInterface $group
	 *
	 * @return UserInterface
	 */
	public function removeGroup(GroupInterface $group): UserInterface;

	/**
	 * Get groups
	 *
	 * @return ArrayCollection
	 */
	public function getGroups();

	/**
	 * Get roles
	 *
	 * @return array
	 */
	public function getRoles(): array;

	/**
	 * Has role
	 *
	 * @param string $roleName
	 *
	 * @return bool
	 */
	public function hasRole(string $roleName): bool;

	/**
	 * Has roles
	 *
	 * @param string[] $roleNames
	 *
	 * @return bool
	 */
	public function hasRoles(array $roleNames): bool;
};