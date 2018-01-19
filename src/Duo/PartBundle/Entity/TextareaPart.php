<?php

namespace Duo\PartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\PartBundle\Form\TextareaPartType;

/**
 * @ORM\Table(name="part_textarea")
 * @ORM\Entity()
 */
class TextareaPart extends AbstractPart
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
		return TextareaPartType::class;
	}

	/**
	 * Get view
	 *
	 * @return string
	 */
	public function getView(): string
	{
		return '@DuoPart/Part/TextareaPart/view.html.twig';
	}
}