<?php

namespace Duo\AdminBundle\EventListener;

use Duo\AdminBundle\Event\Listing\EntityEvent;
use Duo\AdminBundle\Helper\LocaleHelper;
use Duo\CoreBundle\Entity\Property\TranslateInterface;

class TranslateListener
{
	/**
	 * @var LocaleHelper
	 */
	private $localeHelper;

	/**
	 * TranslateEntityListener constructor
	 *
	 * @param LocaleHelper $localeHelper
	 */
	public function __construct(LocaleHelper $localeHelper)
	{
		$this->localeHelper = $localeHelper;
	}

	/**
	 * On pre create event
	 *
	 * @param EntityEvent $event
	 */
	public function preCreate(EntityEvent $event): void
	{
		$this->setLocales($event);
	}

	/**
	 * On pre update event
	 *
	 * @param EntityEvent $event
	 */
	public function preUpdate(EntityEvent $event): void
	{
		$this->setLocales($event);
	}

	/**
	 * Set locales
	 *
	 * @param EntityEvent $event
	 */
	private function setLocales(EntityEvent $event): void
	{
		$entity = $event->getEntity();

		if (!$entity instanceof TranslateInterface)
		{
			return;
		}

		$locales = $this->localeHelper->getLocales();

		if (empty($locales))
		{
			$locales = [ $this->localeHelper->getDefaultLocale() ];
		}

		foreach ($locales as $locale)
		{
			if ($entity->getTranslations()->containsKey($locale))
			{
				continue;
			}

			$entity->translate($locale);
		}

		$entity->mergeNewTranslations();
	}
}