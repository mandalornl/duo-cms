<?php

namespace Duo\AdminBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class LocaleListener
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