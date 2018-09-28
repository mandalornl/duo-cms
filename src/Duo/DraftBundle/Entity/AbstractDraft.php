<?php

namespace Duo\DraftBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\CoreBundle\Entity\Property\IdInterface;
use Duo\CoreBundle\Entity\Property\IdTrait;
use Duo\CoreBundle\Entity\Property\TimestampInterface;
use Duo\CoreBundle\Entity\Property\TimestampTrait;
use Duo\DraftBundle\Entity\DraftInterface as EntityDraftInterface;
use Duo\DraftBundle\Entity\Property\DraftInterface as PropertyDraftInterface;

abstract class AbstractDraft implements IdInterface, TimestampInterface, EntityDraftInterface
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
	 * {@inheritdoc}
	 */
	public function setName(?string $name): DraftInterface
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName(): ?string
	{
		return $this->name;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setData(?array $data): DraftInterface
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
	public function setEntity(?PropertyDraftInterface $entity): EntityDraftInterface
	{
		$this->entity = $entity;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEntity(): ?PropertyDraftInterface
	{
		return $this->entity;
	}
}