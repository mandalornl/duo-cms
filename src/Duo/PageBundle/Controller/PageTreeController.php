<?php

namespace Duo\PageBundle\Controller;

use Duo\BehaviorBundle\Entity\RevisionInterface;
use Duo\BehaviorBundle\Entity\SortInterface;
use Duo\PageBundle\Entity\Page;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route(name="duo_page_tree_")
 *
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED') and has_role('ROLE_ADMIN')")
 */
class PageTreeController extends Controller
{
	/**
	 * Index action
	 *
	 * @Route("/", name="index")
	 * @Method("GET")
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
			'pages' => $this->getPages(),
			'moveToRoute' => $this->generateUrl('duo_page_listing_page_move_to')
		]);
	}

	/**
	 * Children action
	 *
	 * @Route("/{id}/children", name="children", requirements={ "id" = "\d+" })
	 * @Method("GET")
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
				'pages' => $entity->getChildren(),
				'parent' => $entity
			])
		);
	}

	/**
	 * Get pages
	 *
	 * @param int $parentId [optional]
	 *
	 * @return array
	 *
	 * @throws \Throwable
	 */
	private function getPages(int $parentId = null): array
	{
		$builder = $this->getDoctrine()->getRepository(Page::class)
			->createQueryBuilder('e');

		if ($parentId !== null)
		{
			$builder
				->where('e.parent = :parent')
				->setParameter('parent', $parentId);
		}
		else
		{
			$builder->where('e.parent IS NULL');
		}

		$reflectionClass = new \ReflectionClass(Page::class);

		// using latest revision
		if ($reflectionClass->implementsInterface(RevisionInterface::class))
		{
			$builder->andWhere('e.revision = e.id');
		}

		// sort on weight
		if ($reflectionClass->implementsInterface(SortInterface::class))
		{
			$builder->orderBy('e.weight', 'ASC');
		}

		return $builder
			->getQuery()
			->getResult();
	}
}