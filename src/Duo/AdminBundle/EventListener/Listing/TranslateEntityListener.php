<?php

namespace Duo\AdminBundle\EventListener\Listing;

use Duo\AdminBundle\Event\Listing\EntityEvent;
use Duo\AdminBundle\Helper\LocaleHelper;
use Duo\BehaviorBundle\Entity\TranslateInterface;

class TranslateEntityListener
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
	 * On pre add event
	 *
	 * @param EntityEvent $args
	 */
	public function preAdd(EntityEvent $args)
	{
		$this->setLocales($args->getEntity());
	}

	/**
	 * On pre edit event
	 *
	 * @param EntityEvent $args
	 */
	public function preEdit(EntityEvent $args)
	{
		$this->setLocales($args->getEntity());
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