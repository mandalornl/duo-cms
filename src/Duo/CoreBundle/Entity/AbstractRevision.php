<?php

namespace Duo\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\CoreBundle\Entity\Property\IdTrait;
use Duo\CoreBundle\Entity\Property\RevisionInterface as PropertyRevisionInterface;
use Duo\CoreBundle\Entity\Property\TimestampTrait;
use Duo\CoreBundle\Entity\RevisionInterface as EntityRevisionInterface;

abstract class AbstractRevision implements EntityRevisionInterface
{
	use IdTrait;
	use TimestampTrait;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="name", type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var array
	 *
	 * @ORM\Column(name="data", type="json", nullable=true)
	 */
	protected $data;

	/**
	 * @var PropertyRevisionInterface
	 */
	protected $entity;

	/**
	 * {@inheritDoc}
	 */
	public function setName(?string $name): EntityRevisionInterface
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName(): ?string
	{
		return $this->name;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setData(?array $data): EntityRevisionInterface
	{
		$this->data = $data;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getData(): ?array
	{
		return $this->data;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setEntity(?PropertyRevisionInterface $entity): EntityRevisionInterface
	{
		$this->entity = $entity;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getEntity(): ?PropertyRevisionInterface
	{
		return $this->entity;
	}
}
