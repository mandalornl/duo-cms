<?php

namespace AppBundle\Entity\PagePart;

use AppBundle\Form\PagePart\ImagePagePartType;
use Doctrine\ORM\Mapping as ORM;
use Duo\MediaBundle\Entity\Media;
use Duo\PageBundle\Entity\AbstractPagePart;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="duo_page_part_image")
 * @ORM\Entity()
 */
class ImagePagePart extends AbstractPagePart
{
	/**
	 * @var Media
	 *
	 * @ORM\ManyToOne(targetEntity="Duo\MediaBundle\Entity\Media")
	 * @ORM\JoinColumn(name="media_id", referencedColumnName="id", onDelete="SET NULL")
	 * @Assert\NotBlank()
	 */
	private $media;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="alt", type="string", nullable=true)
	 */
	private $alt;

	/**
	 * Set media
	 *
	 * @param Media $media
	 *
	 * @return ImagePagePart
	 */
	public function setMedia(?Media $media): ImagePagePart
	{
		$this->media = $media;

		return $this;
	}

	/**
	 * Get media
	 *
	 * @return Media
	 */
	public function getMedia(): ?Media
	{
		return $this->media;
	}

	/**
	 * Set alt
	 *
	 * @param string $alt
	 *
	 * @return ImagePagePart
	 */
	public function setAlt(?string $alt): ImagePagePart
	{
		$this->alt = $alt;

		return $this;
	}

	/**
	 * Get alt
	 *
	 * @return string
	 */
	public function getAlt(): ?string
	{
		return $this->alt;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPartFormType(): string
	{
		return ImagePagePartType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getView(): string
	{
		return '@App/PagePart/Image/view.html.twig';
	}
}