<?php

namespace Duo\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\CoreBundle\Entity\DraftInterface as EntityDraftInterface;
use Duo\CoreBundle\Entity\Property\IdTrait;
use Duo\CoreBundle\Entity\Property\DraftInterface as PropertyDraftInterface;
use Duo\CoreBundle\Entity\Property\TimestampTrait;

abstract class AbstractDraft implements EntityDraftInterface
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
	 * @var PropertyDraftInterface
	 */
	protected $entity;

	/**
	 * {@inheritDoc}
	 */
	public function setName(?string $name): EntityDraftInterface
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
	public function setData(?array $data): EntityDraftInterface
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
	public function setEntity(?PropertyDraftInterface $entity): EntityDraftInterface
	{
		$this->entity = $entity;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getEntity(): ?PropertyDraftInterface
	{
		return $this->entity;
	}
}
