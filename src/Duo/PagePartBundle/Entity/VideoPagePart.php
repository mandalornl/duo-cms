<?php

namespace Duo\PagePartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\PagePartBundle\Form\VideoPagePartType;

/**
 * @ORM\Table(name="pp_video")
 * @ORM\Entity()
 */
class VideoPagePart extends AbstractPagePart
{
	/**
	 * {@inheritdoc}
	 */
	public function getFormType(): string
	{
		return VideoPagePartType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getView(): string
	{
		return '@DuoPagePart/PagePart/VideoPagePart/view.html.twig';
	}
}