<?php

namespace Duo\PartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\PartBundle\Form\HeadingPartType;

/**
 * @ORM\Table("part_heading")
 * @ORM\Entity()
 */
class HeadingPart extends AbstractPart
{
	/**
	 * @var string
	 *
	 * @ORM\Column(name="type", type="string", nullable=true)
	 */
	private $type;

	/**
	 * Set type
	 *
	 * @param string $type
	 *
	 * @return HeadingPart
	 */
	public function setType(string $type = null): HeadingPart
	{
		$this->type = $type;

		return $this;
	}

	/**
	 * Get type
	 *
	 * @return string
	 */
	public function getType(): ?string
	{
		return $this->type;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFormType(): string
	{
		return HeadingPartType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getView(): string
	{
		return '@DuoPart/Part/HeadingPart/view.html.twig';
	}
}