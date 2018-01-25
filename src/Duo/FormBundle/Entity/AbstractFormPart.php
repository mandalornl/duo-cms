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
}