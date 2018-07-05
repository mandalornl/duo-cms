<?php

namespace Duo\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Duo\CoreBundle\Entity\DeleteInterface;
use Duo\CoreBundle\Entity\DeleteTrait;
use Duo\CoreBundle\Entity\IdInterface;
use Duo\CoreBundle\Entity\IdTrait;
use Duo\CoreBundle\Entity\SlugInterface;
use Duo\CoreBundle\Entity\SlugTrait;
use Duo\CoreBundle\Entity\TimestampInterface;
use Duo\CoreBundle\Entity\TimestampTrait;
use Duo\CoreBundle\Entity\TreeInterface;
use Duo\CoreBundle\Entity\TreeTrait;
use Duo\CoreBundle\Entity\UrlInterface;
use Duo\CoreBundle\Entity\UrlTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="duo_folder")
 * @ORM\Entity(repositoryClass="Duo\MediaBundle\Repository\FolderRepository")
 */
class Folder implements IdInterface,
						TimestampInterface,
						DeleteInterface,
						TreeInterface,
						SlugInterface,
						UrlInterface
{
	use IdTrait;
	use SlugTrait;
	use UrlTrait;
	use DeleteTrait;
	use TreeTrait;
	use TimestampTrait;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="name", type="string", nullable=true)
	 * @Assert\NotBlank()
	 */
	private $name;

	/**
	 * @var Collection
	 *
	 * @ORM\OneToMany(
	 *     targetEntity="Duo\MediaBundle\Entity\File",
	 *     mappedBy="folder",
	 *     cascade={ "persist", "merge", "remove" },
	 *     orphanRemoval=true
	 * )
	 * @ORM\OrderBy({ "name" = "ASC" })
	 */
	private $files;

	/**
	 * Folder constructor
	 */
	public function __construct()
	{
		$this->files = new ArrayCollection();
	}

	/**
	 * Set name
	 *
	 * @param string $name
	 *
	 * @return Folder
	 */
	public function setName(string $name = null): Folder
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName(): ?string
	{
		return $this->name;
	}

	/**
	 * Add file
	 *
	 * @param File $file
	 *
	 * @return Folder
	 */
	public function addFile(File $file): Folder
	{
		$this->files[] = $file;

		return $this;
	}

	/**
	 * Remove file
	 *
	 * @param File $file
	 *
	 * @return Folder
	 */
	public function removeFile(File $file): Folder
	{
		$this->files->removeElement($file);

		return $this;
	}

	/**
	 * Get files
	 *
	 * @return ArrayCollection
	 */
	public function getFiles()
	{
		return $this->files;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getValueToSlugify(): string
	{
		return $this->name;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getValueToUrlize(): string
	{
		return $this->slug;
	}
}