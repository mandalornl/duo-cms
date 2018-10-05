<?php

namespace Duo\TranslatorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\CoreBundle\Entity\Property\IdInterface;
use Duo\CoreBundle\Entity\Property\IdTrait;
use Duo\CoreBundle\Entity\Property\TimestampInterface;
use Duo\CoreBundle\Entity\Property\TimestampTrait;
use Duo\CoreBundle\Entity\Property\TranslateInterface;
use Duo\CoreBundle\Entity\Property\TranslateTrait;
use Duo\CoreBundle\Entity\Property\TranslationInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(
 *     name="duo_translator_entry",
 *     uniqueConstraints={
 *		   @ORM\UniqueConstraint(name="UNIQ_KEYWORD", columns={ "keyword", "domain" })
 *	   },
 *     indexes={
 *		   @ORM\Index(name="IDX_KEYWORD", columns={ "keyword" })
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
	 * @var int
	 */
	const FLAG_NONE = 0;

	/**
	 * @var int
	 */
	const FLAG_NEW = 1;

	/**
	 * @var int
	 */
	const FLAG_UPDATED = 2;

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
	 * @var int
	 *
	 * @ORM\Column(name="flag", type="smallint", options={ "default" = 1 })
	 */
	private $flag = 1;

	/**
	 * Set keyword
	 *
	 * @param string $keyword
	 *
	 * @return Entry
	 */
	public function setKeyword(?string $keyword): Entry
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
	public function setDomain(?string $domain): Entry
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
	 * Set flag
	 *
	 * @param int $flag
	 *
	 * @return Entry
	 */
	public function setFlag(int $flag): Entry
	{
		$this->flag = $flag;

		return $this;
	}

	/**
	 * Get flag
	 *
	 * @return int
	 */
	public function getFlag(): int
	{
		return $this->flag;
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

	/**
	 * {@inheritdoc}
	 */
	public function __toString(): string
	{
		return $this->keyword;
	}
}