<?php

namespace Duo\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\CoreBundle\Entity\CloneTrait;
use Duo\CoreBundle\Entity\Property\DeleteInterface;
use Duo\CoreBundle\Entity\Property\DeleteTrait;
use Duo\CoreBundle\Entity\Property\IdInterface;
use Duo\CoreBundle\Entity\Property\IdTrait;
use Duo\CoreBundle\Entity\Property\TimestampInterface;
use Duo\CoreBundle\Entity\Property\TimestampTrait;
use Duo\CoreBundle\Entity\Property\UuidInterface;
use Duo\CoreBundle\Entity\Property\UuidTrait;
use Duo\TaxonomyBundle\Entity\Property\TaxonomyInterface;
use Duo\TaxonomyBundle\Entity\Property\TaxonomyTrait;

/**
 * @ORM\Table(name="duo_media")
 * @ORM\Entity()
 */
class Media implements IdInterface, TimestampInterface, DeleteInterface, TaxonomyInterface, UuidInterface
{
	use IdTrait;
	use TimestampTrait;
	use DeleteTrait;
	use TaxonomyTrait;
	use CloneTrait;
	use UuidTrait;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="name", type="string", nullable=true)
	 */
	private $name;

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
	 * Set name
	 *
	 * @param string $name
	 *
	 * @return Media
	 */
	public function setName(?string $name): Media
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
	 * Set url
	 *
	 * @param string $url
	 *
	 * @return Media
	 */
	public function setUrl(?string $url): Media
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
	 * @return Media
	 */
	public function setMimeType(?string $mimeType): Media
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
	 * @return Media
	 */
	public function setSize(?int $size): Media
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
	 * @return Media
	 */
	public function setMetadata(?array $metadata): Media
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
	 * Get original basename
	 *
	 * @return string
	 */
	public function getOriginalBasename(): ?string
	{
		return $this->metadata['basename'] ?? null;
	}

	/**
	 * Get original filename
	 *
	 * @return string
	 */
	public function getOriginalFilename(): ?string
	{
		return $this->metadata['filename'] ?? null;
	}

	/**
	 * Get original extension
	 *
	 * @return string
	 */
	public function getOriginalExtension(): ?string
	{
		return $this->metadata['extension'] ?? null;
	}

	/**
	 * Get original width
	 *
	 * @return int
	 */
	public function getOriginalWidth(): ?int
	{
		return $this->metadata['width'] ?? null;
	}

	/**
	 * Get original height
	 *
	 * @return int
	 */
	public function getOriginalHeight(): ?int
	{
		return $this->metadata['height'] ?? null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function __toString(): string
	{
		return $this->name;
	}
}