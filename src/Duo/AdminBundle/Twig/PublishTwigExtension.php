<?php

namespace Duo\AdminBundle\Twig;

use Duo\CoreBundle\Entity\PublishInterface;
use Duo\CoreBundle\Entity\TranslateInterface;
use Duo\CoreBundle\Entity\TranslationInterface;

class PublishTwigExtension extends \Twig_Extension
{
	/**
	 * {@inheritdoc}
	 */
	public function getTests()
	{
		return [
			new \Twig_SimpleTest('published', [$this, 'isPublished'])
		];
	}

	/**
	 * Check whether or not entity is published
	 *
	 * @param object $entity
	 *
	 * @return int
	 */
	public function isPublished($entity): int
	{
		if (!$entity instanceof PublishInterface)
		{
			// check translations
			if ($entity instanceof TranslateInterface)
			{
				// get published translations
				$translations = $entity->getTranslations()->filter(function(TranslationInterface $translation)
				{
					return $translation instanceof PublishInterface && $translation->isPublished();
				});

				// no published translations found
				if (!$translations->count())
				{
					return PublishInterface::NONE;
				}
				else
				{
					// some published translations found
					if ($translations->count() !== $entity->getTranslations()->count())
					{
						return PublishInterface::PARTIAL;
					}
				}

				// all published
				return PublishInterface::ALL;
			}
		}
		else
		{
			// check entity instead
			if ($entity->isPublished())
			{
				return PublishInterface::ALL;
			}
		}

		return PublishInterface::NONE;
	}
}