<?php

namespace Duo\NodeBundle\Controller\AutoComplete;

use Duo\AdminBundle\Helper\ORM\QueryHelper;
use Duo\NodeBundle\Entity\Page;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(name="duo_node_autocomplete_page_")
 */
class PageController extends AbstractController
{
	/**
	 * Search url action
	 *
	 * @Route("/url", name="url")
	 * @Method({"POST", "GET"})
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function searchUrlAction(Request $request)
	{
		$repository = $this->getDoctrine()->getRepository(Page::class);

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
			->select("DISTINCT e.id, CONCAT('/', t.url) text")
			->getQuery()
			->getScalarResult();

		return $this->json([
			'result' => $result,
			'count' => $count
		]);
	}
}