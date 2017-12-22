<?php

namespace Duo\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\AdminBundle\Entity\Behavior\IdableTrait;
use Duo\AdminBundle\Entity\Behavior\TimeStampableInterface;
use Duo\AdminBundle\Entity\Behavior\TimeStampableTrait;
use Duo\AdminBundle\Entity\Behavior\TranslatableInterface;
use Duo\AdminBundle\Entity\Behavior\TranslatableTrait;
use Duo\AdminBundle\Entity\Behavior\TranslationInterface;

/**
 * @ORM\Table(name="taxonomy")
 * @ORM\Entity()
 */
class Taxonomy implements TranslatableInterface, TimeStampableInterface
{
	use IdableTrait;
	use TranslatableTrait;
	use TimeStampableTrait;

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