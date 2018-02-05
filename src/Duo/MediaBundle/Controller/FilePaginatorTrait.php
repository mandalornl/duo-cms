<?php

namespace Duo\MediaBundle\Controller;

use Duo\AdminBundle\Helper\PaginatorHelper;
use Duo\MediaBundle\Entity\File;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

trait FilePaginatorTrait
{
	/**
	 * Get paginator
	 *
	 * @param Request $request
	 * @param int $folderId [optional]
	 *
	 * @return PaginatorHelper
	 */
	protected function getPaginator(Request $request, int $folderId = null): PaginatorHelper
	{
		$page = (int)$request->get('page') ?: null;
		$limit = (int)$request->get('limit') ?: null;

		/**
		 * @var Controller $this
		 */
		$builder = $this->getDoctrine()->getRepository(File::class)
			->createQueryBuilder('e')
			->where('e.deletedAt IS NULL');

		if ($folderId !== null)
		{
			$builder
				->andWhere('e.folder = :folder')
				->setParameter('parent', $folderId);
		}
		else
		{
			$builder->andWhere('e.folder IS NULL');
		}

		// apply filters
		$this->applyFilters($request, $builder);

		// apply sorting or revert to defaults
		if (!$this->applySorting($request, $builder))
		{
			$builder->orderBy('e.name', 'ASC');
		}

		return (new PaginatorHelper($builder))
			->setPage($page)
			->setLimit($limit)
			->createView();
	}
}