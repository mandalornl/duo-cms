<?php

namespace Duo\MediaBundle\Controller;

use Duo\AdminBundle\Controller\FieldTrait;
use Duo\AdminBundle\Controller\FilterTrait;
use Duo\AdminBundle\Controller\RoutePrefixTrait;
use Duo\AdminBundle\Helper\PaginatorHelper;
use Duo\AdminBundle\Listing\Field\Field;
use Duo\AdminBundle\Listing\Filter\DateTimeFilter;
use Duo\AdminBundle\Listing\Filter\NumericFilter;
use Duo\AdminBundle\Listing\Filter\StringFilter;
use Duo\AdminBundle\Twig\TwigContext;
use Duo\MediaBundle\Entity\File;
use Duo\MediaBundle\Entity\Folder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route(name="duo_media_listing_folder_")
 *
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED') and has_role('ROLE_ADMIN')")
 */
class FolderListingController extends Controller
{
	use RoutePrefixTrait;
	use FieldTrait;
	use FilterTrait;

	/**
	 * FileController constructor
	 */
	public function __construct()
	{
		$this->defineFields();
		$this->defineFilters();
	}

	/**
	 * Get type
	 *
	 * @return string
	 */
	protected function getType(): string
	{
		return 'media';
	}

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
	 * @Route("/", name="index", defaults={ "id" = null })
	 * @Route("/{id}", requirements={ "id" = "\d+" })
	 * @Method("GET")
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response
	 *
	 * @throws \Throwable
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
	 * Get default context
	 *
	 * @param array $context [optional]
	 *
	 * @return TwigContext
	 *
	 * @throws \Throwable
	 */
	private function getDefaultContext(array $context = []): TwigContext
	{
		$context = array_merge([
			'menu' => $this->get('duo.admin.menu_builder')->createView(),
			'routePrefix' => $this->getRoutePrefix(),
			'type' => $this->getType()
		], $context);

		return new TwigContext($context);
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
	 * Add entity
	 *
	 * @Route("/add", name="add")
	 * @Method({"GET", "POST"})
	 *
	 * @param Request $request
	 */
	public function addAction(Request $request)
	{

	}

	/**
	 * Edit entity
	 *
	 * @Route("/{id}", name="edit", requirements={ "id" = "\d+" })
	 * @Method({"GET", "POST"})
	 *
	 * @param Request $request
	 * @param int $id
	 */
	public function editAction(Request $request, int $id)
	{

	}

	/**
	 * Destroy enttiy
	 *
	 * @Route("/destroy/{id}", name="destroy", requirements={ "id" = "\d+" })
	 * @Method({"GET", "POST"})
	 *
	 * @param Request $request
	 * @param int $id [optional]
	 */
	public function destroyAction(Request $request, int $id = null)
	{

	}
}