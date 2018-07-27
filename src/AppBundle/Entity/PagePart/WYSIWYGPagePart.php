<?php

namespace AppBundle\Entity\PagePart;

use AppBundle\Form\PagePart\WYSIWYGPagePartType;
use Doctrine\ORM\Mapping as ORM;
use Duo\PageBundle\Entity\AbstractPagePart;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="duo_page_part_wysiwyg")
 * @ORM\Entity()
 */
class WYSIWYGPagePart extends AbstractPagePart
{
	/**
	 * @var string
	 *
	 * @ORM\Column(name="value", type="text", nullable=true)
	 * @Assert\NotBlank()
	 */
	private $value;

	/**
	 * Set value
	 *
	 * @param string $value
	 *
	 * @return WYSIWYGPagePart
	 */
	public function setValue(string $value = null): WYSIWYGPagePart
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
	 * {@inheritdoc}
	 */
	public function getPartFormType(): string
	{
		return WYSIWYGPagePartType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getView(): string
	{
		return '@App/PagePart/WYSIWYG/view.html.twig';
	}
}