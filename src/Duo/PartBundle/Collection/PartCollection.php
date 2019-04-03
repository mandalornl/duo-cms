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
	 * @var array
	 */
	private $snapshot = [];

	/**
	 * @var bool
	 */
	private $dirty = false;

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
	 * {@inheritDoc}
	 */
	protected function doInitialize(): void
	{
		$parts = $this->manager->getRepository(Reference::class)->findParts($this->entity);

		$this->collection = new ArrayCollection($parts);

		$this->takeSnapshot();
	}

	/**
	 * {@inheritDoc}
	 */
	public function set($key, $value): void
	{
		parent::set($key, $value);

		$this->dirty = true;
	}

	/**
	 * {@inheritDoc}
	 */
	public function offsetSet($offset, $value): void
	{
		parent::offsetSet($offset, $value);

		$this->dirty = true;
	}

	/**
	 * {@inheritDoc}
	 */
	public function offsetUnset($offset): void
	{
		parent::offsetUnset($offset);

		$this->dirty = true;
	}

	/**
	 * {@inheritDoc}
	 */
	public function add($element): bool
	{
		$this->dirty = true;

		return parent::add($element);
	}

	/**
	 * {@inheritDoc}
	 */
	public function remove($key): bool
	{
		$this->dirty = true;

		return parent::remove($key);
	}

	/**
	 * {@inheritDoc}
	 */
	public function removeElement($element): bool
	{
		$this->dirty = true;

		return parent::removeElement($element);
	}

	/**
	 * Take snapshot
	 *
	 * @return PartCollection
	 */
	public function takeSnapshot(): PartCollection
	{
		$this->snapshot = $this->collection->toArray();
		$this->dirty = false;

		return $this;
	}

	/**
	 * Get snapshot
	 *
	 * @return array
	 */
	public function getSnapshot(): array
	{
		return $this->snapshot;
	}

	/**
	 * Is dirty
	 *
	 * @return bool
	 */
	public function isDirty(): bool
	{
		return $this->dirty;
	}

	/**
	 * Get insert difference
	 *
	 * @return array
	 */
	public function getInsertDiff(): array
	{
		return array_udiff_assoc(
			$this->collection->toArray(),
			$this->snapshot,
			function($a, $b)
			{
				return $a === $b ? 0 : 1;
			}
		);
	}

	/**
	 * Get delete difference
	 *
	 * @return array
	 */
	public function getDeleteDiff(): array
	{
		return array_udiff_assoc(
			$this->snapshot,
			$this->collection->toArray(),
			function($a, $b)
			{
				return $a === $b ? 0 : 1;
			}
		);
	}

	/**
	 * {@inheritDoc}
	 */
	public function __sleep(): array
	{
		return ['collection', 'initialized'];
	}

	/**
	 * {@inheritDoc}
	 */
	public function __clone()
	{
		if (is_object($this->collection))
		{
			$this->collection = clone $this->collection;
		}

		$this->initialize();

		$this->snapshot = [];
		$this->dirty = true;
	}
}
