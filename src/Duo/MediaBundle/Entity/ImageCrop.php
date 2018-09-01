<?php

namespace Duo\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\CoreBundle\Entity\Property\IdInterface;
use Duo\CoreBundle\Entity\Property\IdTrait;
use Duo\CoreBundle\Entity\Property\TimestampInterface;
use Duo\CoreBundle\Entity\Property\TimestampTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="duo_media_image_crop")
 * @ORM\Entity()
 */
class ImageCrop implements IdInterface, TimestampInterface
{
	use IdTrait;
	use TimestampTrait;

	/**
	 * @var Media
	 *
	 * @ORM\ManyToOne(targetEntity="Duo\MediaBundle\Entity\Media")
	 * @ORM\JoinColumn(name="media_id", referencedColumnName="id", onDelete="CASCADE")
	 * @Assert\NotBlank()
	 */
	private $media;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="crop", type="string", nullable=true)
	 */
	private $crop;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="ratio", type="string", nullable=true)
	 */
	private $ratio;

	/**
	 * Set media
	 *
	 * @param Media $media
	 *
	 * @return ImageCrop
	 */
	public function setMedia(Media $media = null): ImageCrop
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
	 * Set crop
	 *
	 * @param string $crop
	 *
	 * @return ImageCrop
	 */
	public function setCrop(string $crop = null): ImageCrop
	{
		$this->crop = $crop;

		return $this;
	}

	/**
	 * Get crop
	 *
	 * @return string
	 */
	public function getCrop(): ?string
	{
		return $this->crop;
	}

	/**
	 * Set ratio
	 *
	 * @param string $ratio
	 *
	 * @return ImageCrop
	 */
	public function setRatio(string $ratio = null): ImageCrop
	{
		$this->ratio = $ratio;

		return $this;
	}

	/**
	 * Get ratio
	 *
	 * @return string
	 */
	public function getRatio(): ?string
	{
		return $this->ratio;
	}
}