<?php

namespace Duo\AdminBundle\EventListener;

use Duo\AdminBundle\Helper\LocaleHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleListener implements EventSubscriberInterface
{
	/**
	 * @var LocaleHelper
	 */
	private $localeHelper;

	/**
	 * LocaleListener constructor
	 *
	 * @param LocaleHelper $localeHelper
	 */
	public function __construct(LocaleHelper $localeHelper)
	{
		$this->localeHelper = $localeHelper;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			KernelEvents::REQUEST => [ 'onKernelRequest', 16 ]
		];
	}

	/**
	 * On kernel request event
	 *
	 * @param KernelEvent $event
	 */
	public function onKernelRequest(KernelEvent $event): void
	{
		$request = $event->getRequest();

		if (!$request->query->has('locale'))
		{
			return;
		}

		$locale = $request->query->get('locale', $this->localeHelper->getDefaultLocale());

		if (!preg_match('#^[a-z]{2}(?:[-_][a-zA-Z]{2})?$#', $locale))
		{
			return;
		}

		$request->setLocale($locale);
	}
}