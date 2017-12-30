<?php

namespace Duo\FormBundle\Form;

use Doctrine\ORM\Mapping as ORM;

abstract class AbstractFormChoiceType extends AbstractFormType implements FormChoiceTypeInterface
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
	 */
	protected $choices;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="empty_value", type="string", nullable=true)
	 */
	protected $emptyValue;

	/**
	 * {@inheritdoc}
	 */
	public function setExpanded(bool $expanded = false): FormChoiceTypeInterface
	{
		$this->expanded = $expanded;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getExpanded(): bool
	{
		return $this->expanded;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setMultiple(bool $multiple = false): FormChoiceTypeInterface
	{
		$this->multiple = $multiple;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getMultiple(): bool
	{
		return $this->multiple;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setChoices(string $choices = null): FormChoiceTypeInterface
	{
		$this->choices = $choices;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getChoices(): ?string
	{
		return $this->choices;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setEmptyValue(string $emptyValue = null): FormChoiceTypeInterface
	{
		$this->emptyValue = $emptyValue;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEmptyValue(): ?string
	{
		return $this->emptyValue;
	}
}