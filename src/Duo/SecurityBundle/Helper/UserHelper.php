<?php

namespace Duo\SecurityBundle\Helper;

use Duo\SecurityBundle\Entity\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserHelper
{
	/**
	 * @var TokenStorageInterface
	 */
	private $tokenStorage;

	/**
	 * UserHelper constructor
	 *
	 * @param TokenStorageInterface $tokenStorage
	 */
	public function __construct(TokenStorageInterface $tokenStorage)
	{
		$this->tokenStorage = $tokenStorage;
	}

	/**
	 * Get user
	 *
	 * @return UserInterface
	 */
	public function getUser(): ?UserInterface
	{
		if (($token = $this->tokenStorage->getToken()) === null)
		{
			return null;
		}

		// e.g. anonymous token
		if (!is_object($user = $token->getUser()))
		{
			return null;
		}

		return $user;
	}
}