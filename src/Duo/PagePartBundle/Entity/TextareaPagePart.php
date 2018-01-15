<?php

namespace Duo\PagePartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\PagePartBundle\Form\TextareaPagePartType;

/**
 * @ORM\Table(name="pp_textarea")
 * @ORM\Entity()
 */
class TextareaPagePart extends AbstractPagePart
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
		return TextareaPagePartType::class;
	}

	/**
	 * Get view
	 *
	 * @return string
	 */
	public function getView(): string
	{
		return '@DuoPagePart/PagePart/TextareaPagePart/view.html.twig';
	}
}