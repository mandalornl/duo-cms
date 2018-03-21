<?php

namespace Duo\SecurityBundle\Helper;

use Duo\AdminBundle\Helper\RequestHelper;
use Duo\SecurityBundle\Entity\UserInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginHelper
{
	/**
	 * @var RequestHelper
	 */
	private $requestHelper;

	/**
	 * @var TokenStorageInterface
	 */
	private $tokenStorage;

	/**
	 * @var EventDispatcherInterface
	 */
	private $eventDispatcher;

	/**
	 * LoginHelper constructor
	 *
	 * @param RequestHelper $requestHelper
	 * @param TokenStorageInterface $tokenStorage
	 * @param EventDispatcherInterface $eventDispatcher
	 */
	public function __construct(RequestHelper $requestHelper,
								TokenStorageInterface $tokenStorage,
								EventDispatcherInterface $eventDispatcher
	)
	{
		$this->requestHelper = $requestHelper;
		$this->tokenStorage = $tokenStorage;
		$this->eventDispatcher = $eventDispatcher;
	}

	/**
	 * Manual login
	 *
	 * @param UserInterface $user
	 * @param string $firewall
	 *
	 * @return bool
	 */
	public function manualLogin(UserInterface $user, string $firewall): bool
	{
		if (($request = $this->requestHelper->getRequest()) === null)
		{
			return false;
		}

		$token = new UsernamePasswordToken($user, null, $firewall, $user->getRoles());
		$this->tokenStorage->setToken($token);

		$request->getSession()->set("_security_{$firewall}", serialize($token));

		$this->eventDispatcher->dispatch('security.interactive_login', new InteractiveLoginEvent($request, $token));

		return true;
	}
}