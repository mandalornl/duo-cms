<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\CoreBundle\Entity\Property\TranslationInterface;
use Duo\PageBundle\Entity\AbstractPage;
use Duo\PageBundle\Entity\PageTranslationInterface;

/**
 * @ORM\Table(name="duo_page")
 * @ORM\Entity(repositoryClass="Duo\PageBundle\Repository\PageRepository")
 */
class Page extends AbstractPage
{
	/**
	 * {@inheritdoc}
	 *
	 * @return PageTranslationInterface|TranslationInterface
	 */
	public function translate(string $locale = null, bool $fallback = true): PageTranslationInterface
	{
		return $this->doTranslate($locale, $fallback);
	}

	/**
	 * {@inheritdoc}
	 */
	public function __toString(): string
	{
		return $this->translate()->getTitle();
	}
}