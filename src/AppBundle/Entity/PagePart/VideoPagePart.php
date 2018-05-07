<?php

namespace AppBundle\Entity\PagePart;

use AppBundle\Form\PagePart\VideoPagePartType;
use Doctrine\ORM\Mapping as ORM;
use Duo\PageBundle\Entity\AbstractPagePart;

/**
 * @ORM\Table(name="duo_page_part_video")
 * @ORM\Entity()
 */
class VideoPagePart extends AbstractPagePart
{
	/**
	 * {@inheritdoc}
	 */
	public function getPartFormType(): string
	{
		return VideoPagePartType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getView(): string
	{
		return '@App/PagePart/Video/view.html.twig';
	}
}