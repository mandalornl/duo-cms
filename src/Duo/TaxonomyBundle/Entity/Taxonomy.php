<?php

namespace Duo\TaxonomyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\CoreBundle\Entity\IdInterface;
use Duo\CoreBundle\Entity\IdTrait;
use Duo\CoreBundle\Entity\TimestampInterface;
use Duo\CoreBundle\Entity\TimestampTrait;
use Duo\CoreBundle\Entity\TranslateInterface;
use Duo\CoreBundle\Entity\TranslateTrait;
use Duo\CoreBundle\Entity\TranslationInterface;

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