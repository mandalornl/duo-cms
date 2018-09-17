<?php

namespace AppBundle\Entity\PagePart;

use AppBundle\Form\PagePart\VideoPagePartType;
use Doctrine\ORM\Mapping as ORM;
use Duo\PageBundle\Entity\AbstractPagePart;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="duo_page_part_video")
 * @ORM\Entity()
 */
class VideoPagePart extends AbstractPagePart
{
	/**
	 * @var string
	 *
	 * @ORM\Column(name="value", type="string", nullable=true)
	 * @Assert\NotBlank()
	 */
	private $value;

	/**
	 * Set value
	 *
	 * @param string $value
	 *
	 * @return VideoPagePart
	 */
	public function setValue(?string $value): VideoPagePart
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