<?php

namespace Duo\PagePartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\PagePartBundle\Form\WYSIWYGPagePartType;

/**
 * @ORM\Table(name="pp_wysiwyg")
 * @ORM\Entity()
 */
class WYSIWYGPagePart extends AbstractPagePart
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
		return WYSIWYGPagePartType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getView(): string
	{
		return '@DuoPagePart/PagePart/WYSIWYGPagePart/view.html.twig';
	}
}