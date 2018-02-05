<?php

namespace Duo\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\BehaviorBundle\Entity\DeleteInterface;
use Duo\BehaviorBundle\Entity\DeleteTrait;
use Duo\BehaviorBundle\Entity\IdTrait;
use Duo\BehaviorBundle\Entity\TimeStampInterface;
use Duo\BehaviorBundle\Entity\TimeStampTrait;

/**
 * @ORM\Table(name="file")
 * @ORM\Entity(repositoryClass="Duo\MediaBundle\Repository\FileRepository")
 */
class File implements TimeStampInterface, DeleteInterface
{
	use IdTrait;
	use TimeStampTrait;
	use DeleteTrait;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="name", type="string", nullable=true)
	 */
	private $name;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="uuid", type="string", length=16, nullable=true)
	 */
	private $uuid;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="url", type="string", nullable=true)
	 */
	private $url;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="mime_type", type="string", length=48, nullable=true)
	 */
	private $mimeType;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="size", type="bigint", nullable=true)
	 */
	private $size;

	/**
	 * @var array
	 *
	 * @ORM\Column(name="metadata", type="json", nullable=true)
	 */
	private $metadata;

	/**
	 * @var Folder
	 *
	 * @ORM\ManyToOne(targetEntity="Duo\MediaBundle\Entity\Folder", inversedBy="files")
	 * @ORM\JoinColumn(name="folder_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	private $folder;

	/**
	 * Set name
	 *
	 * @param string $name
	 *
	 * @return File
	 */
	public function setName(string $name = null): File
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
	 * Set uuid
	 *
	 * @param string $uuid
	 *
	 * @return File
	 */
	public function setUuid(string $uuid = null): File
	{
		$this->uuid = $uuid;

		return $this;
	}

	/**
	 * Get uuid
	 *
	 * @return string
	 */
	public function getUuid(): ?string
	{
		return $this->uuid;
	}

	/**
	 * Set url
	 *
	 * @param string $url
	 *
	 * @return File
	 */
	public function setUrl(string $url = null): File
	{
		$this->url = $url;

		return $this;
	}

	/**
	 * Get url
	 *
	 * @return string
	 */
	public function getUrl(): ?string
	{
		return $this->url;
	}

	/**
	 * Set mimeType
	 *
	 * @param string $mimeType
	 *
	 * @return File
	 */
	public function setMimeType(string $mimeType = null): File
	{
		$this->mimeType = $mimeType;

		return $this;
	}

	/**
	 * Get mimeType
	 *
	 * @return string
	 */
	public function getMimeType(): ?string
	{
		return $this->mimeType;
	}

	/**
	 * Set size
	 *
	 * @param int $size
	 *
	 * @return File
	 */
	public function setSize(int $size = null): File
	{
		$this->size = $size;

		return $this;
	}

	/**
	 * Get size
	 *
	 * @return int
	 */
	public function getSize(): ?int
	{
		return $this->size;
	}

	/**
	 * Set metadata
	 *
	 * @param array $metadata
	 *
	 * @return $this
	 */
	public function setMetadata(array $metadata = null)
	{
		$this->metadata = $metadata;

		return $this;
	}

	/**
	 * Get metadata
	 *
	 * @return array
	 */
	public function getMetadata(): ?array
	{
		return $this->metadata;
	}

	/**
	 * Set folder
	 *
	 * @param Folder $folder
	 *
	 * @return File
	 */
	public function setFolder(Folder $folder = null): File
	{
		$this->folder = $folder;

		return $this;
	}

	/**
	 * Get folder
	 *
	 * @return Folder
	 */
	public function getFolder(): ?Folder
	{
		return $this->folder;
	}
}