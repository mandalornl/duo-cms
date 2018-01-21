<?php

namespace Duo\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\PartBundle\Entity\AbstractPart;

abstract class AbstractPagePart extends AbstractPart implements PagePartInterface
{
	/**
	 * @var string
	 *
	 * @ORM\Column(name="value", type="string", nullable=true)
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