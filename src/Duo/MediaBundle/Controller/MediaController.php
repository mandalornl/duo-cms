<?php

namespace Duo\MediaBundle\Controller;

use Duo\AdminBundle\Controller\RoutePrefixTrait;
use Duo\AdminBundle\Helper\PaginatorHelper;
use Duo\MediaBundle\Entity\File;
use Duo\MediaBundle\Entity\Folder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/media", name="duo_media_listing_media_")
 */
class MediaController extends Controller
{
	use RoutePrefixTrait;

	/**
	 * Index action
	 *
	 * @Route("/{id}", name="index", requirements={ "id" = "\d+" }, methods={ "GET" })
	 *
	 * @param Request $request
	 * @param int $id [optional]
	 *
	 * @return Response
	 */
	public function indexAction(Request $request, int $id = null): Response
	{
		$entity = null;

		if ($id !== null)
		{
			$entity = $this->getDoctrine()->getRepository(Folder::class)->find($id);

			if ($entity === null)
			{
				$className = Folder::class;

				throw $this->createNotFoundException("Entity '{$className}::{$id}' not found");
			}

			$folders = $entity->getChildren();
		}
		else
		{
			$folders = $this->getFolders();
		}

		return $this->render('@DuoMedia/Listing/media.html.twig', [
			'folders' => $folders,
			'paginator' => $this->getPaginator($request, $entity)
		]);
	}

	/**
	 * Get folders
	 *
	 * @return Folder[]
	 */
	private function getFolders(): array
	{
		return $this->getDoctrine()->getRepository(Folder::class)
			->createQueryBuilder('e')
			->where('e.parent IS NULL AND e.deletedAt IS NULL')
			->getQuery()
			->getResult();
	}

	/**
	 * Get paginator
	 *
	 * @param Request $request
	 * @param Folder $folder [optional]
	 *
	 * @return PaginatorHelper
	 */
	private function getPaginator(Request $request, Folder $folder = null): PaginatorHelper
	{
		$page = (int)$request->get('page') ?: null;
		$limit = (int)$request->get('limit') ?: null;

		$builder = $this->getDoctrine()->getRepository(File::class)
			->createQueryBuilder('e')
			->where('e.deletedAt IS NULL');

		if ($folder !== null)
		{
			$builder
				->andWhere('e.folder = :folder')
				->setParameter('folder', $folder);
		}
		else
		{
			$builder->andWhere('e.folder IS NULL');
		}

		// TODO: apply search

		return (new PaginatorHelper($builder))
			->setPage($page)
			->setLimit($limit)
			->createView();
	}
}