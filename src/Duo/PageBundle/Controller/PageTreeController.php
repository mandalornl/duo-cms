<?php

namespace Duo\PageBundle\Controller;

use Doctrine\ORM\Query\Expr\Join;
use Duo\AdminBundle\Tools\Menu\MenuBuilder;
use Duo\PageBundle\Entity\PageInterface;
use Duo\PageBundle\Repository\PageRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/page-tree", name="duo_page_tree_")
 *
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED') and has_role('ROLE_ADMIN')")
 */
class PageTreeController extends Controller
{
	/**
	 * Index action
	 *
	 * @Route("/", name="index", methods={ "GET" })
	 *
	 * @param Request $request
	 *
	 * @return Response
	 *
	 */
	public function indexAction(Request $request): Response
	{
		return $this->render('@DuoPage/Menu/view.html.twig', [
			'menu' => $this->get(MenuBuilder::class)->createView(),
			'pages' => $this->getPages($request),
			'moveToUrl' => $this->generateUrl('duo_page_listing_page_move_to')
		]);
	}

	/**
	 * Children action
	 *
	 * @Route("/{id}/children", name="children", requirements={ "id" = "\d+" }, methods={ "GET" })
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return JsonResponse
	 *
	 * @throws \Throwable
	 */
	public function childrenAction(Request $request, int $id): JsonResponse
	{
		$entity = $this->getDoctrine()->getRepository(PageInterface::class)->find($id);

		if ($entity === null)
		{
			$className = PageInterface::class;

			return $this->json([
				'error' => "Entity '{$className}::{$id}' not found"
			]);
		}

		return $this->json([
			'html' => $this->renderView('@DuoPage/Menu/tree.html.twig', [
				'pages' => $this->getPages($request, $entity),
				'parent' => $entity
			])
		]);
	}

	/**
	 * Get pages
	 *
	 * @param Request $request
	 * @param PageInterface $parent [optional]
	 *
	 * @return array
	 */
	private function getPages(Request $request, PageInterface $parent = null): array
	{
		/**
		 * @var PageRepository $repository
		 */
		$repository = $this->getDoctrine()->getRepository(PageInterface::class);

		$builder = $repository
			->createQueryBuilder('e')
			->join('e.translations', 't', Join::WITH, 't.translatable = e AND t.locale = :locale')
			->where('e.revision = e AND e.deletedAt IS NULL')
			->orderBy('e.weight', 'ASC')
			->addOrderBy('t.title', 'ASC')
			->setParameter('locale', $request->getLocale());

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