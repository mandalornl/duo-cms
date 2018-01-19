<?php

namespace Duo\AdminBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface
{
	/**
	 * @var string
	 */
	private $defaultLocale = 'nl';

	/**
	 * LocaleListener constructor
	 *
	 * @param string $defaultLocale
	 */
	public function __construct(string $defaultLocale)
	{
		$this->defaultLocale = $defaultLocale;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			KernelEvents::REQUEST => [['onKernelRequest', -10]]
		];
	}

	/**
	 * On kernel request
	 *
	 * @param GetResponseEvent $event
	 */
	public function onKernelRequest(GetResponseEvent $event): void
	{
		$request = $event->getRequest();

		if (!$request->query->has('locale'))
		{
			return;
		}

		$locale = $request->query->get('locale', $this->defaultLocale);
		if (!preg_match('#^[a-z]{2}(?:[-_][a-zA-Z]{2})?$#', $locale))
		{
			return;
		}

		$request->setLocale($locale);
	}
}