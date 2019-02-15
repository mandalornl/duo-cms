<?php

namespace Duo\PageBundle\Controller\Listing;

use Duo\CoreBundle\Controller\Listing\AbstractTreeController;
use Duo\CoreBundle\Entity\Property\TreeInterface;
use Duo\PageBundle\Entity\PageInterface;
use Duo\PageBundle\Repository\PageRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/page-tree", name="duo_page_listing_page_")
 */
class PageTreeController extends AbstractTreeController
{
	use PageConfigurationTrait;

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/", name="tree_index", methods={ "GET" })
	 */
	public function indexAction(Request $request): Response
	{
		return $this->doIndexAction($request);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route(
	 *     "/{id}/children",
	 *     name="tree_children",
	 *     requirements={ "id" = "\d+" },
	 *     defaults={ "_format" = "json" },
	 *     methods={ "GET" }
	 * )
	 */
	public function childrenAction(Request $request, int $id): JsonResponse
	{
		return $this->doChildrenAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getChildren(Request $request, TreeInterface $parent = null): array
	{
		/**
		 * @var PageRepository $repository
		 */
		$repository = $this->getDoctrine()->getRepository(PageInterface::class);

		$builder = $repository->createDefaultQueryBuilder($request->getLocale())
			->orderBy('e.weight', 'ASC')
			->addOrderBy('t.title', 'ASC');

		if ($parent !== null)
		{
			$builder
				->andWhere('e.parent = :parent')
				->setParameter('parent', $parent);
		}
		else
		{
			$builder->andWhere('e.parent IS NULL');
		}

		return $builder
			->getQuery()
			->getResult();
	}
}
