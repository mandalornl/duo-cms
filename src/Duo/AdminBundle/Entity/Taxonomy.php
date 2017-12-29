<?php

namespace Duo\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\BehaviorBundle\Entity\IdTrait;
use Duo\BehaviorBundle\Entity\TimeStampInterface;
use Duo\BehaviorBundle\Entity\TimeStampTrait;
use Duo\BehaviorBundle\Entity\TranslateInterface;
use Duo\BehaviorBundle\Entity\TranslateTrait;
use Duo\BehaviorBundle\Entity\TranslationInterface;

/**
 * @ORM\Table(name="taxonomy")
 * @ORM\Entity()
 */
class Taxonomy implements TranslateInterface, TimeStampInterface
{
	use IdTrait;
	use TranslateTrait;
	use TimeStampTrait;

	/**
	 * {@inheritdoc}
	 *
	 * @return TaxonomyTranslation|TranslationInterface
	 */
	public function translate(string $locale = null, bool $fallback = true): TaxonomyTranslation
	{
		return $this->doTranslate($locale, $fallback);
	}
}