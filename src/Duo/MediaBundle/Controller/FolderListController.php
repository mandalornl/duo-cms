<?php

namespace Duo\MediaBundle\Controller;

use Duo\AdminBundle\Configuration\Field\Field;
use Duo\AdminBundle\Configuration\Filter\DateTimeFilter;
use Duo\AdminBundle\Configuration\Filter\NumericFilter;
use Duo\AdminBundle\Configuration\Filter\StringFilter;
use Duo\AdminBundle\Controller\AbstractListController;
use Duo\AdminBundle\Helper\PaginatorHelper;
use Duo\MediaBundle\Entity\File;
use Duo\MediaBundle\Entity\Folder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/media/folder", name="duo_media_listing_folder_")
 */
class FolderListController extends AbstractListController
{
	use FolderConfigurationTrait;

	/**
	 * {@inheritdoc}
	 */
	protected function defineFields(): void
	{
		$this
			->addField(new Field('name', 'duo.media.listing.field.name'))
			->addField(new Field('mimeType', 'duo.media.listing.field.type'))
			->addField(new Field('size', 'duo.media.listing.field.size', true, '@DuoMedia/Listing/Field/size.html.twig'))
			->addField(new Field('createdAt', 'duo.media.listing.field.created_at'))
			->addField(new Field('modifiedAt', 'duo.media.listing.field.modified_at'));
	}

	/**
	 * {@inheritdoc}
	 */
	protected function defineFilters(): void
	{
		$this
			->addFilter(new StringFilter('name', 'duo.media.listing.filter.name'))
			->addFilter(new StringFilter('mimeType', 'duo.media.listing.filter.type'))
			->addFilter(new NumericFilter('size', 'duo.media.listing.filter.byte_size'))
			->addFilter(new DateTimeFilter('createdAt', 'duo.media.listing.filter.created'))
			->addFilter(new DateTimeFilter('modifiedAt', 'duo.media.listing.filter.modified'));
	}

	/**
	 * Index
	 *
	 * @Route("/{id}", name="index", requirements={ "id" = "\d+" }, methods={ "GET" })
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response
	 *
	 * @throws \Throwable
	 */
	public function advancedIndexAction(Request $request, int $id = null): Response
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

		return $this->render('@DuoMedia/Listing/folder.html.twig', (array)$this->getDefaultContext([
			'folders' => $folders,
			'paginator' => $this->getPaginator($request, $entity),
			'list' => [
				'filterForm' => call_user_func(function() use ($request)
				{
					if (($form = $this->getFilterForm($request)) === null)
					{
						return null;
					}

					return $form->createView();
				}),
				'fields' => $this->fields,
				'sorting' => $this->getSorting($request)
			]
		]));
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
	protected function getPaginator(Request $request, Folder $folder = null): PaginatorHelper
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

		// apply filters
		$this->applyFilters($request, $builder);

		// apply sorting or revert to defaults
		if (!$this->applySorting($request, $builder))
		{
			$builder->orderBy('e.name', 'ASC');
		}

		return (new PaginatorHelper($builder))
			->setPage($page)
			->setLimit($limit)
			->createView();
	}

	/**
	 * {@inheritdoc}
	 */
	public function isDeletable(): bool
	{
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function indexAction(Request $request): Response
	{
		// dummy
		return new Response('', 404);
	}
}