<?php

namespace Duo\MediaBundle\Controller;

use Duo\AdminBundle\Controller\AbstractAutoCompleteController;
use Duo\AdminBundle\Helper\ORM\QueryHelper;
use Duo\MediaBundle\Entity\Folder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/media/folder/autocomplete", name="duo_media_autocomplete_folder_")
 */
class FolderAutoCompleteController extends AbstractAutoCompleteController
{
	/**
	 * Search url action
	 *
	 * @Route("/url", name="url")
	 * @Method({"GET", "POST"})
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function searchUrlAction(Request $request)
	{
		$repository = $this->getDoctrine()->getRepository(Folder::class);

		$builder = $repository
			->createQueryBuilder('e')
			->where('e.name LIKE :keyword OR e.url LIKE :keyword')
			->orderBy('e.name', 'ASC')
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
			->select("DISTINCT e.id, CONCAT('/', e.url) text")
			->getQuery()
			->getScalarResult();

		return $this->json([
			'result' => $result,
			'count' => $count
		]);
	}
}