<?php

namespace Duo\FormBundle\Entity\FormPart;

use Doctrine\ORM\Mapping as ORM;

abstract class AbstractTextFormPart extends AbstractFormPart implements TextFormPartInterface
{
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
	 * {@inheritDoc}
	 */
	public function setRequired(bool $required): TextFormPartInterface
	{
		$this->required = $required;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function isRequired(): bool
	{
		return $this->required;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setPlaceholder(?string $placeholder): TextFormPartInterface
	{
		$this->placeholder = $placeholder;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getPlaceholder(): ?string
	{
		return $this->placeholder;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setErrorMessage(?string $errorMessage): TextFormPartInterface
	{
		$this->errorMessage = $errorMessage;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getErrorMessage(): ?string
	{
		return $this->errorMessage;
	}
}
