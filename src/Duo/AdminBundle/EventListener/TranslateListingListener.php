<?php

namespace Duo\AdminBundle\EventListener;

use Duo\AdminBundle\Event\ListingEvent;
use Duo\AdminBundle\Helper\LocaleHelper;
use Duo\BehaviorBundle\Entity\TranslateInterface;

class TranslateListingListener
{
	/**
	 * @var LocaleHelper
	 */
	private $localeHelper;

	/**
	 * TranslateListingListener constructor
	 *
	 * @param LocaleHelper $localeHelper
	 */
	public function __construct(LocaleHelper $localeHelper)
	{
		$this->localeHelper = $localeHelper;
	}

	/**
	 * On pre add event
	 *
	 * @param ListingEvent $event
	 */
	public function preAdd(ListingEvent $event)
	{
		$this->setLocales($event->getEntity());
	}

	/**
	 * On pre edit event
	 *
	 * @param ListingEvent $event
	 */
	public function preEdit(ListingEvent $event)
	{
		$this->setLocales($event->getEntity());
	}

	/**
	 * Set locales
	 *
	 * @param object $entity
	 */
	private function setLocales($entity)
	{
		if (!$entity instanceof TranslateInterface)
		{
			return;
		}

		$locales = $this->localeHelper->getLocales();
		if (empty($locales))
		{
			$locales = [$this->localeHelper->getDefaultLocale()];
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