<?php

namespace Softmedia\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Softmedia\AdminBundle\Entity\Behavior\IdableTrait;
use Softmedia\AdminBundle\Entity\Behavior\TimeStampableTrait;
use Softmedia\AdminBundle\Entity\Behavior\TranslatableInterface;
use Softmedia\AdminBundle\Entity\Behavior\TranslatableTrait;
use Softmedia\AdminBundle\Entity\Behavior\TranslationInterface;

/**
 * @ORM\Table(name="taxonomy")
 * @ORM\Entity()
 */
class Taxonomy implements TranslatableInterface
{
	use IdableTrait;
	use TimeStampableTrait;
	use TranslatableTrait;

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