<?php

namespace Duo\FormBundle\Entity\FormPart;

use Doctrine\ORM\Mapping as ORM;
use Duo\PartBundle\Entity\AbstractPart;
use Duo\PartBundle\Entity\Property\PartInterface;
use Symfony\Component\Validator\Constraints as Assert;

abstract class AbstractFormPart extends AbstractPart implements FormPartInterface
{
	/**
	 * @var PartInterface
	 *
	 * @ORM\ManyToOne(targetEntity="Duo\FormBundle\Entity\FormTranslation")
	 * @ORM\JoinColumn(name="entity_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $entity;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="label", type="string", nullable=true)
	 * @Assert\NotBlank()
	 */
	protected $label;

	/**
	 * {@inheritDoc}
	 */
	public function setLabel(?string $label): FormPartInterface
	{
		$this->label = $label;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getLabel(): ?string
	{
		return $this->label;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getFormOptions(): array
	{
		return [];
	}
}
