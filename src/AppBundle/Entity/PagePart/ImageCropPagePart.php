<?php

namespace AppBundle\Entity\PagePart;

use AppBundle\Form\PagePart\ImageCropPagePartType;
use Doctrine\ORM\Mapping as ORM;
use Duo\MediaBundle\Entity\ImageCrop;
use Duo\PageBundle\Entity\AbstractPagePart;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="duo_page_part_image_crop")
 * @ORM\Entity()
 */
class ImageCropPagePart extends AbstractPagePart
{
	/**
	 * @var ImageCrop
	 *
	 * @ORM\OneToOne(targetEntity="Duo\MediaBundle\Entity\ImageCrop", cascade={ "persist"}, orphanRemoval=true)
	 * @ORM\JoinColumn(name="image_crop_id", referencedColumnName="id", onDelete="SET NULL")
	 * @Assert\NotBlank()
	 */
	private $imageCrop;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="alt", type="string", nullable=true)
	 */
	private $alt;

	/**
	 * Set imageCrop
	 *
	 * @param ImageCrop $imageCrop
	 *
	 * @return ImageCropPagePart
	 */
	public function setImageCrop(?ImageCrop $imageCrop): ImageCropPagePart
	{
		$this->imageCrop = $imageCrop;

		return $this;
	}

	/**
	 * Get imageCrop
	 *
	 * @return ImageCrop
	 */
	public function getImageCrop(): ?ImageCrop
	{
		return $this->imageCrop;
	}

	/**
	 * Set alt
	 *
	 * @param string $alt
	 *
	 * @return ImageCropPagePart
	 */
	public function setAlt(?string $alt): ImageCropPagePart
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
		return ImageCropPagePartType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getView(): string
	{
		return '@App/PagePart/ImageCrop/view.html.twig';
	}

	/**
	 * {@inheritdoc}
	 */
	public function __clone()
	{
		// be sure to clone crop entity
		$this->imageCrop = clone $this->imageCrop;
	}
}