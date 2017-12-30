<?php

namespace Duo\FormBundle\Form;

use Doctrine\ORM\Mapping as ORM;

abstract class AbstractFormType implements FormTypeInterface
{
	/**
	 * @var string
	 *
	 * @ORM\Column(name="type", type="string", nullable=true)
	 */
	protected $type;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="label", type="string", nullable=true)
	 */
	protected $label;

	/**
	 * @var bool
	 *
	 * @ORM\Column(name="required", type="boolean", options={ "default" = 0 })
	 */
	protected $required = false;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="placeholder", type="string", nullable=true)
	 */
	protected $placeholder;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="error_message", type="string", nullable=true)
	 */
	protected $errorMessage;

	/**
	 * {@inheritdoc}
	 */
	public function setType(string $type = null): FormTypeInterface
	{
		$this->type = $type;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getType(): ?string
	{
		return $this->type;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setLabel(string $label = null): FormTypeInterface
	{
		$this->label = $label;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getLabel(): ?string
	{
		return $this->label;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setRequired(bool $required = false): FormTypeInterface
	{
		$this->required = $required;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRequired(): bool
	{
		return $this->required;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setPlaceholder(string $placeholder = null): FormTypeInterface
	{
		$this->placeholder = $placeholder;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPlaceHolder(): ?string
	{
		return $this->placeholder;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setErrorMessage(string $errorMessage = null): FormTypeInterface
	{
		$this->errorMessage = $errorMessage;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getErrorMessage(): ?string
	{
		return $this->errorMessage;
	}
}