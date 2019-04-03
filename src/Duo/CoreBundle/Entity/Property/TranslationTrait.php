<?php

namespace Duo\CoreBundle\Entity\Property;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait TranslationTrait
{
	use IdTrait;

	/**
	 * @var TranslateInterface
	 */
	protected $entity;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="locale", type="string", length=5, nullable=true)
	 * @Assert\NotBlank()
	 * @Assert\Locale()
	 */
	protected $locale;

	/**
	 * {@inheritDoc}
	 */
	public function setEntity(?TranslateInterface $entity): TranslationInterface
	{
		$this->entity = $entity;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getEntity(): ?TranslateInterface
	{
		return $this->entity;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setLocale(string $locale): TranslationInterface
	{
		$this->locale = $locale;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getLocale(): ?string
	{
		return $this->locale;
	}
}
