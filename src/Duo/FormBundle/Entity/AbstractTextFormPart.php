<?php

namespace Duo\FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

abstract class AbstractTextFormPart extends AbstractFormPart implements TextFormPartInterface
{
	/**
	 * @var bool
	 *
	 * @ORM\Column(name="required", type="boolean", options={ "default" = 0})
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
	public function setRequired(bool $required = false): FormPartInterface
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
	public function setPlaceholder(string $placeholder = null): FormPartInterface
	{
		$this->placeholder = $placeholder;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPlaceholder(): ?string
	{
		return $this->placeholder;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setErrorMessage(string $errorMessage = null): FormPartInterface
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