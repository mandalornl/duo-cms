<?php

namespace Duo\PageBundle\Controller;

use Duo\AdminBundle\Controller\AbstractAutoCompleteController;
use Duo\AdminBundle\Helper\ORM\QueryHelper;
use Duo\PageBundle\Entity\PageInterface;
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
	 * @Route("/url", name="url", methods={ "GET", "POST" })
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function searchUrlAction(Request $request): JsonResponse
	{
		/**
		 * @var PageRepository $repository
		 */
		$repository = $this->getDoctrine()->getRepository(PageInterface::class);

		$builder = $repository
			->createQueryBuilder('e')
			->join('e.translations', 't')
			->where('t.locale = :locale AND (e.name LIKE :keyword OR t.url LIKE :keyword)')
			->orderBy('t.url', 'ASC')
			->setParameter('locale', $request->getLocale())
			->setParameter('keyword', QueryHelper::escapeLike($request->get('q', '')));

		// don't include self or offspring
		if (($id = (int)$request->get('id')))
		{
			$ids = array_merge([$id], $repository->getOffspringIds($id));

			$builder
				->andWhere('e.id NOT IN(:ids)')
				->setParameter('ids', $ids);
		}

		$count = $this->getCount($builder);

		$this->setFirstResultAndMaxResults($request, $builder);

		$result = $builder
			->select('DISTINCT e.id, t.url text')
			->getQuery()
			->getScalarResult();

		return $this->json([
			'result' => $result,
			'count' => $count
		]);
	}
}