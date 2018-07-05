<?php

namespace Duo\TranslatorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\CoreBundle\Entity\IdInterface;
use Duo\CoreBundle\Entity\IdTrait;
use Duo\CoreBundle\Entity\TimestampInterface;
use Duo\CoreBundle\Entity\TimestampTrait;
use Duo\CoreBundle\Entity\TranslateInterface;
use Duo\CoreBundle\Entity\TranslateTrait;
use Duo\CoreBundle\Entity\TranslationInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(
 *     name="duo_translator_entry",
 *     uniqueConstraints={
 *		   @ORM\UniqueConstraint(name="keyword_uniq", columns={ "keyword", "domain" })
 *	   },
 *     indexes={
 *		   @ORM\Index(name="keyword_idx", columns={ "keyword" })
 *	   }
 * )
 * @ORM\Entity()
 * @UniqueEntity(fields={ "keyword", "domain" }, message="duo.translator.errors.keyword_used")
 */
class Entry implements IdInterface, TranslateInterface, TimestampInterface
{
	use IdTrait;
	use TranslateTrait;
	use TimestampTrait;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="keyword", type="string", nullable=true)
	 * @Assert\NotBlank()
	 */
	private $keyword;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="domain", type="string", nullable=true)
	 * @Assert\NotBlank()
	 */
	private $domain;

	/**
	 * Set keyword
	 *
	 * @param string $keyword
	 *
	 * @return Entry
	 */
	public function setKeyword(string $keyword = null): Entry
	{
		$this->keyword = $keyword;

		return $this;
	}

	/**
	 * Get keyword
	 *
	 * @return string
	 */
	public function getKeyword(): ?string
	{
		return $this->keyword;
	}

	/**
	 * Set domain
	 *
	 * @param string $domain
	 *
	 * @return Entry
	 */
	public function setDomain(string $domain = null): Entry
	{
		$this->domain = $domain;

		return $this;
	}

	/**
	 * Get domain
	 *
	 * @return string
	 */
	public function getDomain(): ?string
	{
		return $this->domain;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @return EntryTranslation|TranslationInterface
	 */
	public function translate(string $locale = null, bool $fallback = true): EntryTranslation
	{
		return $this->doTranslate($locale, $fallback);
	}
}