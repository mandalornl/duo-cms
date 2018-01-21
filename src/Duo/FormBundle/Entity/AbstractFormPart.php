<?php

namespace Duo\FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\PartBundle\Entity\AbstractPart;
use Symfony\Component\Validator\Constraints as Assert;

abstract class AbstractFormPart extends AbstractPart implements FormPartInterface
{
	/**
	 * @var string
	 *
	 * @ORM\Column(name="label", type="string", nullable=false)
	 * @Assert\NotBlank()
	 */
	protected $label;

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
	public function setLabel(string $label = null): FormPartInterface
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