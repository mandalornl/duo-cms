<?php

namespace Duo\TaxonomyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\CoreBundle\Entity\Property\IdInterface;
use Duo\CoreBundle\Entity\Property\IdTrait;
use Duo\CoreBundle\Entity\Property\TimestampInterface;
use Duo\CoreBundle\Entity\Property\TimestampTrait;
use Duo\CoreBundle\Entity\Property\TranslateInterface;
use Duo\CoreBundle\Entity\Property\TranslateTrait;
use Duo\CoreBundle\Entity\Property\TranslationInterface;

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

	/**
	 * {@inheritdoc}
	 */
	public function __toString(): string
	{
		return $this->translate()->getName();
	}
}
