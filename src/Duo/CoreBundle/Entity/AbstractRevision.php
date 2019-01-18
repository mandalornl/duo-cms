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
	 * {@inheritdoc}
	 */
	public function setData(?array $data): EntityRevisionInterface
	{
		$this->data = $data;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getData(): ?array
	{
		return $this->data;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setEntity(?PropertyRevisionInterface $entity): EntityRevisionInterface
	{
		$this->entity = $entity;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEntity(): ?PropertyRevisionInterface
	{
		return $this->entity;
	}
}
