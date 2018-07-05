<?php

namespace Duo\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\PartBundle\Entity\AbstractPart;
use Symfony\Component\Validator\Constraints as Assert;

abstract class AbstractPagePart extends AbstractPart implements PagePartInterface
{
	/**
	 * @var string
	 *
	 * @ORM\Column(name="value", type="string", nullable=true)
	 * @Assert\NotBlank()
	 */
	protected $value;

	/**
	 * {@inheritdoc}
	 */
	public function setValue(string $value = null): PagePartInterface
	{
		$this->value = $value;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getValue(): ?string
	{
		return $this->value;
	}
}