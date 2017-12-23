<?php

namespace Duo\AdminBundle\Entity\Behavior;

use Doctrine\ORM\Mapping as ORM;

trait TranslationTrait
{
	/**
	 * @var int
	 *
	 * @ORM\Id()
	 * @ORM\Column(name="id", type="bigint")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

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
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setTranslatable(TranslateInterface $translatable): TranslationInterface
	{
		$this->translatable = $translatable;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTranslatable(): TranslateInterface
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