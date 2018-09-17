<?php

namespace Duo\CoreBundle\Entity\Property;

use Doctrine\ORM\Mapping as ORM;

trait TranslationTrait
{
	use IdTrait;

	/**
	 * @var TranslateInterface
	 */
	protected $translatable;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="locale", type="string", length=5, nullable=true)
	 */
	protected $locale;

	/**
	 * {@inheritdoc}
	 */
	public function setTranslatable(?TranslateInterface $translatable): TranslationInterface
	{
		$this->translatable = $translatable;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTranslatable(): ?TranslateInterface
	{
		return $this->translatable;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setLocale(string $locale): TranslationInterface
	{
		$this->locale = $locale;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getLocale(): ?string
	{
		return $this->locale;
	}
}