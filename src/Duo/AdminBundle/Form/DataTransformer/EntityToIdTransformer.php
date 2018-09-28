<?php

namespace Duo\AdminBundle\Form\DataTransformer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class EntityToIdTransformer implements DataTransformerInterface
{
	/**
	 * @var EntityManagerInterface
	 */
	private $manager;

	/**
	 * @var string
	 */
	private $entityClass;

	/**
	 * EntityToIdTransformer constructor
	 *
	 * @param EntityManagerInterface $manager
	 * @param string $entityClass
	 */
	public function __construct(EntityManagerInterface $manager, string $entityClass)
	{
		$this->manager = $manager;
		$this->entityClass = $entityClass;
	}

	/**
	 * Transform
	 *
	 * @param object $entity
	 *
	 * @return int
	 */
	public function transform($entity)
	{
		if ($entity === null || !$entity instanceof $this->entityClass)
		{
			return null;
		}

		return $entity->getId();
	}

	/**
	 * Reverse transform
	 *
	 * @param int $id
	 *
	 * @return object
	 */
	public function reverseTransform($id)
	{
		if ((int)$id === 0)
		{
			return null;
		}

		$entity = $this->manager->getRepository($this->entityClass)->find($id);

		if ($entity === null)
		{
			throw new TransformationFailedException("Entity '{$this->entityClass}::{$id}' not found");
		}

		return $entity;
	}
}