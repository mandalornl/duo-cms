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
	 * {@inheritdoc}
	 */
	public function setEntity(?TranslateInterface $entity): TranslationInterface
	{
		$this->entity = $entity;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEntity(): ?TranslateInterface
	{
		return $this->entity;
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