<?php

namespace Duo\PageBundle\Controller;

use Duo\BehaviorBundle\Entity\RevisionInterface;
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
			'pages' => $this->getPages()
		]);
	}

	/**
	 * Children action
	 *
	 * @Route("/children/{parent}", name="children", requirements={ "parent" = "\d+" })
	 * @Method("GET")
	 *
	 * @param Request $request
	 * @param int $parent
	 *
	 * @return JsonResponse
	 *
	 * @throws \Throwable
	 */
	public function childrenAction(Request $request, int $parent): JsonResponse
	{
		return $this->json(
			$this->renderView('@DuoPage/Menu/tree.html.twig', [
				'pages' => $this->getPages($parent)
			])
		);
	}

	/**
	 * Get pages
	 *
	 * @param int $parent
	 *
	 * @return array
	 *
	 * @throws \Throwable
	 */
	private function getPages(int $parent = null): array
	{
		$builder = $this->getDoctrine()->getRepository(Page::class)
			->createQueryBuilder('e');

		if ($parent !== null)
		{
			$builder
				->where('e.parent = :parent')
				->setParameter('parent', $parent);
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

		return $builder
			->getQuery()
			->getResult();
	}
}