<?php

namespace Duo\CoreBundle\Repository;

use Duo\CoreBundle\Entity\Property\SortInterface as PropertySortInterface;

interface SortInterface
{
	/**
	 * Find previous to sort
	 *
	 * @param PropertySortInterface $entity
	 *
	 * @return PropertySortInterface
	 */
	public function findPrevToSort(PropertySortInterface $entity): ?PropertySortInterface;

	/**
	 * Find previous all to sort
	 *
	 * @param PropertySortInterface $entity
	 *
	 * @return PropertySortInterface[]
	 */
	public function findPrevAllToSort(PropertySortInterface $entity): array;

	/**
	 * Find next all to sort
	 *
	 * @param PropertySortInterface $entity
	 *
	 * @return PropertySortInterface[]
	 */
	public function findNextAllToSort(PropertySortInterface $entity): array;

	/**
	 * Find next entity to sort
	 *
	 * @param PropertySortInterface $entity
	 *
	 * @return PropertySortInterface
	 */
	public function findNextToSort(PropertySortInterface $entity): ?PropertySortInterface;

	/**
	 * Find siblings to sort
	 *
	 * @param PropertySortInterface $entity
	 *
	 * @return PropertySortInterface[]
	 */
	public function findSiblingsToSort(PropertySortInterface $entity): array;
}
