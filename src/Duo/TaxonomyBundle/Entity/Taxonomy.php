<?php

namespace Duo\TaxonomyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\BehaviorBundle\Entity\IdInterface;
use Duo\BehaviorBundle\Entity\IdTrait;
use Duo\BehaviorBundle\Entity\TimestampInterface;
use Duo\BehaviorBundle\Entity\TimestampTrait;
use Duo\BehaviorBundle\Entity\TranslateInterface;
use Duo\BehaviorBundle\Entity\TranslateTrait;
use Duo\BehaviorBundle\Entity\TranslationInterface;

/**
 * @ORM\Table(name="duo_taxonomy")
 * @ORM\Entity()
 */
class Taxonomy implements IdInterface, TranslateInterface, TimestampInterface
{
	use IdTrait;
	use TranslateTrait;
	use TimestampTrait;

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