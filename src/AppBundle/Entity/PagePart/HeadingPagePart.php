<?php

namespace AppBundle\Entity\PagePart;

use AppBundle\Form\PagePart\HeadingPagePartType;
use Doctrine\ORM\Mapping as ORM;
use Duo\PageBundle\Entity\AbstractPagePart;

/**
 * @ORM\Table(name="duo_page_part_heading")
 * @ORM\Entity()
 */
class HeadingPagePart extends AbstractPagePart
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
	 * @return HeadingPagePart
	 */
	public function setType(string $type = null): HeadingPagePart
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
	public function getPartFormType(): string
	{
		return HeadingPagePartType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getView(): string
	{
		return '@App/PagePart/Heading/view.html.twig';
	}
}