<?php

namespace AppBundle\Entity\PagePart;

use AppBundle\Form\PagePart\HeadingPagePartType;
use Doctrine\ORM\Mapping as ORM;
use Duo\PageBundle\Entity\PagePart\AbstractPagePart;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="duo_page_part_heading")
 * @ORM\Entity()
 */
class HeadingPagePart extends AbstractPagePart
{
	/**
	 * @var string
	 *
	 * @ORM\Column(name="value", type="string", nullable=true)
	 * @Assert\NotBlank()
	 */
	private $value;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="type", type="string", nullable=true)
	 */
	private $type;

	/**
	 * Set value
	 *
	 * @param string $value
	 *
	 * @return HeadingPagePart
	 */
	public function setValue(?string $value): HeadingPagePart
	{
		$this->value = $value;

		return $this;
	}

	/**
	 * Get value
	 *
	 * @return string
	 */
	public function getValue(): ?string
	{
		return $this->value;
	}

	/**
	 * Set type
	 *
	 * @param string $type
	 *
	 * @return HeadingPagePart
	 */
	public function setType(?string $type): HeadingPagePart
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
	 * {@inheritDoc}
	 */
	public function getPartFormType(): string
	{
		return HeadingPagePartType::class;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getView(): string
	{
		return '@App/PagePart/Heading/view.html.twig';
	}
}
