<?php

namespace AppBundle\Entity\PagePart;

use AppBundle\Form\PagePart\TextPagePartType;
use Doctrine\ORM\Mapping as ORM;
use Duo\PageBundle\Entity\AbstractPagePart;

/**
 * @ORM\Table(name="page_part_text")
 * @ORM\Entity()
 */
class TextPagePart extends AbstractPagePart
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
	public function getPartFormType(): string
	{
		return TextPagePartType::class;
	}

	/**
	 * Get view
	 *
	 * @return string
	 */
	public function getView(): string
	{
		return '@App/PagePart/Text/view.html.twig';
	}
}