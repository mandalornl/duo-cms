<?php

namespace Duo\AdminBundle\Helper;

use Duo\BehaviorBundle\Entity\TranslateInterface;
use Duo\BehaviorBundle\Entity\TreeInterface;
use Duo\BehaviorBundle\Entity\UrlInterface;

class BreadcrumbHelper
{
	/**
	 * Get breadcrumbs
	 *
	 * @param object $entity
	 *
	 * @return array
	 */
	public function getBreadcrumbs($entity)
	{
		if (!$entity instanceof UrlInterface)
		{
			if (!$entity instanceof TranslateInterface)
			{
				return null;
			}

			$translation = $entity->getTranslations()->first();

			if (!$translation instanceof UrlInterface)
			{
				return null;
			}
		}

		$breadcrumbs = [];

		$iterations = 100;

		do
		{
			$breadcrumbs[] = $entity;

			$entity = $entity instanceof TreeInterface ? $entity->getParent() : null;
		} while ($entity !== null && $iterations--);

		return array_reverse($breadcrumbs);
	}
}