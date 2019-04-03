<?php

namespace Duo\FormBundle\Entity\FormPart;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

abstract class AbstractChoiceFormPart extends AbstractTextFormPart implements ChoiceFormPartInterface
{
	/**
	 * @var bool
	 *
	 * @ORM\Column(name="expanded", type="boolean", options={ "default" = 0 })
	 */
	protected $expanded = false;

	/**
	 * @var bool
	 *
	 * @ORM\Column(name="multiple", type="boolean", options={ "default" = 0 })
	 */
	protected $multiple = false;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="choices", type="text", nullable=true)
	 * @Assert\NotBlank()
	 */
	protected $choices;

	/**
	 * {@inheritDoc}
	 */
	public function setExpanded(bool $expanded): ChoiceFormPartInterface
	{
		$this->expanded = $expanded;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function isExpanded(): bool
	{
		return $this->expanded;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setMultiple(bool $multiple): ChoiceFormPartInterface
	{
		$this->multiple = $multiple;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function isMultiple(): bool
	{
		return $this->multiple;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setChoices(?string $choices): ChoiceFormPartInterface
	{
		$this->choices = $choices;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getChoices(): ?string
	{
		return $this->choices;
	}
}
