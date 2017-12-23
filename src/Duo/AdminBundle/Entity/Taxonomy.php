<?php

namespace Duo\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\AdminBundle\Entity\Behavior\IdTrait;
use Duo\AdminBundle\Entity\Behavior\TimeStampInterface;
use Duo\AdminBundle\Entity\Behavior\TimeStampTrait;
use Duo\AdminBundle\Entity\Behavior\TranslateInterface;
use Duo\AdminBundle\Entity\Behavior\TranslateTrait;
use Duo\AdminBundle\Entity\Behavior\TranslationInterface;

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