<?php

namespace AppBundle\Entity\PagePart;

use AppBundle\Form\PagePart\FilePagePartType;
use Doctrine\ORM\Mapping as ORM;
use Duo\MediaBundle\Entity\Media;
use Duo\PageBundle\Entity\PagePart\AbstractPagePart;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="duo_page_part_file")
 * @ORM\Entity()
 */
class FilePagePart extends AbstractPagePart
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
	 * Set media
	 *
	 * @param Media $media
	 *
	 * @return FilePagePart
	 */
	public function setMedia(?Media $media): FilePagePart
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
	 * {@inheritdoc}
	 */
	public function getPartFormType(): string
	{
		return FilePagePartType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getView(): string
	{
		return '@App/PagePart/File/view.html.twig';
	}
}