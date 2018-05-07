<?php

namespace Duo\PageBundle\Controller;

use Doctrine\ORM\Query\Expr\Join;
use Duo\PageBundle\Entity\Page;
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
	 * @throws \Throwable
	 */
	public function indexAction(Request $request): Response
	{
		return $this->render('@DuoPage/Menu/view.html.twig', [
			'menu' => $this->get('duo.admin.menu_builder')->createView(),
			'pages' => $this->getPages($request),
			'moveToRoute' => $this->generateUrl('duo_page_listing_page_move_to')
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
		$entity = $this->getDoctrine()->getRepository(Page::class)->find($id);

		if ($entity === null)
		{
			$className = Page::class;

			return $this->json([
				'error' => "Entity '{$className}::{$id}' not found"
			]);
		}

		return $this->json(
			$this->renderView('@DuoPage/Menu/tree.html.twig', [
				'pages' => $this->getPages($request, $entity),
				'parent' => $entity
			])
		);
	}

	/**
	 * Get pages
	 *
	 * @param Request $request
	 * @param Page $parent [optional]
	 *
	 * @return array
	 */
	private function getPages(Request $request, Page $parent = null): array
	{
		$builder = $this->getDoctrine()->getRepository(Page::class)
			->createQueryBuilder('e')
			->join('e.translations', 't', Join::WITH, 't.locale = :locale')
			->setParameter('locale', $request->getLocale())
			->where('e.revision = e.id')
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