<?php

namespace Duo\PartBundle\Collection;

use Doctrine\Common\Collections\AbstractLazyCollection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Duo\PartBundle\Entity\Property\PartInterface;
use Duo\PartBundle\Entity\Reference;

class PartCollection extends AbstractLazyCollection
{
	/**
	 * @var EntityManagerInterface
	 */
	private $manager;

	/**
	 * @var PartInterface
	 */
	private $entity;

	/**
	 * PartCollection constructor
	 *
	 * @param EntityManagerInterface $manager
	 * @param PartInterface $entity
	 */
	public function __construct(EntityManagerInterface $manager, PartInterface $entity)
	{
		$this->manager = $manager;
		$this->entity = $entity;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function doInitialize(): void
	{
		$parts = $this->manager->getRepository(Reference::class)->findParts($this->entity);

		$this->collection = new ArrayCollection($parts);
	}
}