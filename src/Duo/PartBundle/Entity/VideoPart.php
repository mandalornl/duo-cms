<?php

namespace Duo\PartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\PartBundle\Form\VideoPartType;

/**
 * @ORM\Table(name="part_video")
 * @ORM\Entity()
 */
class VideoPart extends AbstractPart
{
	/**
	 * {@inheritdoc}
	 */
	public function getFormType(): string
	{
		return VideoPartType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getView(): string
	{
		return '@DuoPart/Part/VideoPart/view.html.twig';
	}
}