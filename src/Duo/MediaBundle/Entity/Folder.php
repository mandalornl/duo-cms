<?php

namespace Duo\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Duo\BehaviorBundle\Entity\DeleteInterface;
use Duo\BehaviorBundle\Entity\DeleteTrait;
use Duo\BehaviorBundle\Entity\IdTrait;
use Duo\BehaviorBundle\Entity\SlugInterface;
use Duo\BehaviorBundle\Entity\SlugTrait;
use Duo\BehaviorBundle\Entity\TimeStampInterface;
use Duo\BehaviorBundle\Entity\TimeStampTrait;
use Duo\BehaviorBundle\Entity\TreeInterface;
use Duo\BehaviorBundle\Entity\TreeTrait;
use Duo\BehaviorBundle\Entity\UrlInterface;
use Duo\BehaviorBundle\Entity\UrlTrait;

/**
 * @ORM\Table(name="folder")
 * @ORM\Entity(repositoryClass="Duo\MediaBundle\Repository\FolderRepository")
 */
class Folder implements TimeStampInterface, DeleteInterface, TreeInterface, SlugInterface, UrlInterface
{
	use IdTrait;
	use SlugTrait;
	use UrlTrait;
	use DeleteTrait;
	use TreeTrait;
	use TimeStampTrait;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="name", type="string", nullable=true)
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