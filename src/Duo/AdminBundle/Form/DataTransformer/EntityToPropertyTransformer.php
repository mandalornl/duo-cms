<?php

namespace Duo\AdminBundle\Form\DataTransformer;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class EntityToPropertyTransformer implements DataTransformerInterface
{
	/**
	 * @var EntityManagerInterface
	 */
	private $manager;

	/**
	 * @var string
	 */
	private $className;

	/**
	 * @var string|\Closure
	 */
	private $property;

	/**
	 * @var bool
	 */
	private $multiple;

	/**
	 * EntityToPropertyTransformer constructor
	 *
	 * @param EntityManagerInterface $manager
	 * @param string $className
	 * @param string|\Closure $property [optional]
	 * @param bool $multiple [optional]
	 */
	public function __construct(
		EntityManagerInterface $manager,
		string $className,
		$property = null,
		bool $multiple = false
	)
	{
		$this->manager = $manager;
		$this->className = $className;
		$this->property = $property;
		$this->multiple = $multiple;
	}

	/**
	 * {@inheritdoc}
	 */
	public function transform($value)
	{
		return $this->multiple
			? $this->transformMultiple($value)
			: $this->transformSingle($value);
	}

	/**
	 * Transform multiple
	 *
	 * @param Collection $entities
	 *
	 * @return array
	 */
	private function transformMultiple($entities)
	{
		if ($entities === null || !count($entities))
		{
			return [];
		}

		$accessor = PropertyAccess::createPropertyAccessor();

		$data = [];
		foreach ($entities as $entity)
		{
			$data[$entity->getId()] = $this->getLabel($entity, $accessor);
		}

		return $data;
	}

	/**
	 * Transform single
	 *
	 * @param object $entity
	 *
	 * @return array
	 */
	private function transformSingle($entity)
	{
		if ($entity === null)
		{
			return [];
		}

		return [
			$entity->getId() => $this->getLabel($entity)
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function reverseTransform($value)
	{
		return $this->multiple
			? $this->reverseTransformMultiple($value)
			: $this->reverseTransformSingle($value);
	}

	/**
	 * Reverse transform multiple
	 *
	 * @param int[] $ids
	 *
	 * @return array
	 */
	private function reverseTransformMultiple($ids): array
	{
		if (!is_array($ids) || !count($ids))
		{
			return [];
		}

		return $this->manager->createQueryBuilder()
			->select('e')
			->from($this->className, 'e')
			->where('e.id IN(:ids)')
			->setParameter('ids', $ids)
			->getQuery()
			->getResult();
	}

	/**
	 * Reverse transform single
	 *
	 * @param int $id
	 *
	 * @return object
	 */
	private function reverseTransformSingle($id)
	{
		if ((int)$id === 0)
		{
			return null;
		}

		$entity = $this->manager->getRepository($this->className)->find($id);

		if ($entity === null)
		{
			throw new TransformationFailedException("Entity '{$this->className}::{$id}' not found");
		}

		return $entity;
	}

	/**
	 * Get label
	 *
	 * @param object $entity
	 * @param PropertyAccessorInterface $accessor [optional]
	 *
	 * @return string
	 */
	private function getLabel(object $entity, PropertyAccessorInterface $accessor = null): string
	{
		if (is_callable($this->property))
		{
			return call_user_func_array($this->property, [ $entity ]);
		}

		if ($accessor === null && $this->property !== null)
		{
			$accessor = PropertyAccess::createPropertyAccessor();
		}

		return $this->property === null
			? (string)$entity
			: $accessor->getValue($entity, $this->property);
	}
}