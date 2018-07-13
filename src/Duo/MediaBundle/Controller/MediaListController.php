<?php

namespace Duo\MediaBundle\Controller;

use Doctrine\ORM\QueryBuilder;
use Duo\AdminBundle\Configuration\Field\Field;
use Duo\AdminBundle\Configuration\Filter\DateTimeFilter;
use Duo\AdminBundle\Configuration\Filter\NumericFilter;
use Duo\AdminBundle\Configuration\Filter\StringFilter;
use Duo\AdminBundle\Controller\AbstractListController;
use Duo\TaxonomyBundle\Configuration\Filter\TaxonomyFilter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/media", name="duo_media_listing_media_")
 */
class MediaListController extends AbstractListController
{
	use MediaConfigurationTrait;

	/**
	 * {@inheritdoc}
	 */
	protected function defineFields(): void
	{
		$this
			->addField(new Field('name', 'duo.media.listing.field.name'))
			->addField(new Field('mimeType', 'duo.media.listing.field.type'))
			->addField(new Field('size', 'duo.media.listing.field.size', true, '@DuoMedia/Listing/Field/size.html.twig'))
			->addField(new Field('taxonomies', 'duo.media.listing.field.taxonomies', false, '@DuoTaxonomy/Listing/Field/taxonomy.html.twig'))
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
			->addFilter(new TaxonomyFilter('id', 'duo.media.listing.filter.taxonomies'))
			->addFilter(new DateTimeFilter('createdAt', 'duo.media.listing.filter.created'))
			->addFilter(new DateTimeFilter('modifiedAt', 'duo.media.listing.filter.modified'));
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/", name="index", methods={ "GET" })
	 */
	public function indexAction(Request $request): Response
	{
		// store view
		if ($request->query->has('view'))
		{
			$request->getSession()->set("view_{$this->getType()}", $request->query->get('view'));
		}

		return $this->render($this->getListTemplate(), (array)$this->getDefaultContext([
			'paginator' => $this->getPaginator($request),
			'list' => array_merge([
				'filterForm' => $this->getFilterFormView($request),
				'searchForm' => $this->getSearchFormView($request),
				'fields' => $this->fields,
				'sorting' => $this->getSorting($request),
				'actions' => $this->getListActions(),
				'view' => $request->getSession()->get("view_{$this->getType()}", 'list')
			], $this->getListBehaviors())
		]));
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getListTemplate(): string
	{
		return '@DuoMedia/Listing/list.html.twig';
	}

	/**
	 * {@inheritdoc}
	 */
	protected function isDeletable(): bool
	{
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function defaultSorting(QueryBuilder $builder): void
	{
		$builder->orderBy('e.name', 'ASC');
	}
}