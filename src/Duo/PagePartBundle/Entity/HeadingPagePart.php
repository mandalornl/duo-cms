<?php

namespace Duo\PagePartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\PagePartBundle\Form\HeadingPagePartType;

/**
 * @ORM\Table("pp_heading")
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
	public function getFormType(): string
	{
		return HeadingPagePartType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getView(): string
	{
		return '@DuoPagePart/PagePart/HeadingPagePart/view.html.twig';
	}
}