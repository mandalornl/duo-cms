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
	private $entityManager;

	/**
	 * @var string
	 */
	private $entityClass;

	/**
	 * EntityToIdTransformer constructor
	 *
	 * @param EntityManagerInterface $entityManager
	 * @param string $entityClass
	 */
	public function __construct(EntityManagerInterface $entityManager, string $entityClass)
	{
		$this->entityManager = $entityManager;
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

		$entity = $this->entityManager->getRepository($this->entityClass)->find($id);
		if ($entity === null)
		{
			throw new TransformationFailedException("Entity '{$this->entityClass}::{$id}' not found");
		}

		return $entity;
	}
}