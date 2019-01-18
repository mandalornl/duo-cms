<?php

namespace Duo\PageBundle\Controller;

use Doctrine\ORM\Query\Expr\Join;
use Duo\AdminBundle\Controller\AbstractAutoCompleteController;
use Duo\AdminBundle\Tools\ORM\Query;
use Duo\PageBundle\Repository\PageRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/page/autocomplete", name="duo_page_autocomplete_page_")
 */
class PageAutoCompleteController extends AbstractAutoCompleteController
{
	/**
	 * Search url action
	 *
	 * @Route("/url", name="url", methods={ "GET" })
	 *
	 * @param Request $request
	 * @param PageRepository $pageRepository
	 *
	 * @return JsonResponse
	 */
	public function searchUrlAction(Request $request, PageRepository $pageRepository): JsonResponse
	{
		$builder = $pageRepository
			->createQueryBuilder('e')
			->join('e.translations', 't', Join::WITH, 't.locale = :locale')
			->where('e.deletedAt IS NULL')
			->andWhere('(e.name LIKE :keyword OR t.url LIKE :keyword)')
			->orderBy('t.url', 'ASC')
			->setParameter('locale', $request->getLocale())
			->setParameter('keyword', Query::escapeLike($request->get('q', '')));

		// don't include self or offspring
		if (($id = (int)$request->get('id')))
		{
			$ids = array_merge([$id], $pageRepository->getOffspringIds($id));

			$builder
				->andWhere('e.id NOT IN(:ids)')
				->setParameter('ids', $ids);
		}

		$count = $this->getCount($builder);

		$this->setFirstResultAndMaxResults($request, $builder);

		$result = $builder
			->select("DISTINCT e.id, CONCAT('/', t.url) text")
			->getQuery()
			->getScalarResult();

		return $this->json([
			'result' => $result,
			'count' => $count
		]);
	}
}
