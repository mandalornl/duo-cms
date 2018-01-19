<?php

namespace Duo\PartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\PartBundle\Form\WYSIWYGPartType;

/**
 * @ORM\Table(name="part_wysiwyg")
 * @ORM\Entity()
 */
class WYSIWYGPart extends AbstractPart
{
	/**
	 * @var string
	 *
	 * @ORM\Column(name="value", type="text", nullable=true)
	 */
	protected $value;

	/**
	 * {@inheritdoc}
	 */
	public function getFormType(): string
	{
		return WYSIWYGPartType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getView(): string
	{
		return '@DuoPart/Part/WYSIWYGPart/view.html.twig';
	}
}