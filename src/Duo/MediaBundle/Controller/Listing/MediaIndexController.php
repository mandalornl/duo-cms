<?php

namespace Duo\MediaBundle\Controller\Listing;

use Doctrine\ORM\QueryBuilder;
use Duo\AdminBundle\Configuration\Field\Field;
use Duo\AdminBundle\Configuration\Filter\DateTimeFilter;
use Duo\AdminBundle\Configuration\Filter\NumericFilter;
use Duo\AdminBundle\Configuration\Filter\StringFilter;
use Duo\AdminBundle\Controller\Listing\AbstractIndexController;
use Duo\TaxonomyBundle\Configuration\Field\TaxonomyField;
use Duo\TaxonomyBundle\Configuration\Filter\TaxonomyFilter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/media", name="duo_media_listing_media_")
 */
class MediaIndexController extends AbstractIndexController
{
	use MediaConfigurationTrait;

	/**
	 * {@inheritDoc}
	 */
	protected function defineFields(Request $request): void
	{
		$this
			->addField(new Field('name', 'duo_media.listing.field.name'))
			->addField(new Field('mimeType', 'duo_media.listing.field.type'))
			->addField(
				(new Field('size', 'duo_media.listing.field.size'))
					->setTemplate('@DuoMedia/Listing/Field/size.html.twig')
			)
			->addField(new TaxonomyField('taxonomies', 'duo_media.listing.field.taxonomies'))
			->addField(new Field('createdAt', 'duo_media.listing.field.created_at'))
			->addField(new Field('modifiedAt', 'duo_media.listing.field.modified_at'));
	}

	/**
	 * {@inheritDoc}
	 */
	protected function defineFilters(Request $request): void
	{
		$this
			->addFilter(new StringFilter('name', 'duo_media.listing.filter.name'))
			->addFilter(new StringFilter('mimeType', 'duo_media.listing.filter.type'))
			->addFilter(new NumericFilter('size', 'duo_media.listing.filter.byte_size'))
			->addFilter(new TaxonomyFilter('id', 'duo_media.listing.filter.taxonomies'))
			->addFilter(new DateTimeFilter('createdAt', 'duo_media.listing.filter.created'))
			->addFilter(new DateTimeFilter('modifiedAt', 'duo_media.listing.filter.modified'));

		// filter media type
		if ($request->query->has('mediaType'))
		{
			$filter = (new StringFilter('mimeType', 'duo_media.listing.filter.type'))
				->setData([
					'operator' => $request->query->get('mediaType') === 'image' ? 'startsWith' : 'notStartsWith',
					'value' => 'image/'
				])
				->setFormOptions([
					'disabled' => true
				]);

			$session = $request->getSession();
			$sessionName = $this->getSessionName($request, 'filter');

			$session->set($sessionName, array_merge($session->get($sessionName, []), [
				$filter->getUid() => $filter->getData()
			]));

			$this->addFilter($filter);
		}
	}

	/**
	 * {@inheritDoc}
	 *
	 * @Route("/", name="index", methods={ "GET" })
	 */
	public function indexAction(Request $request): Response
	{
		return $this->doIndexAction($request);
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getIndexTemplate(): string
	{
		return '@DuoMedia/Listing/index.html.twig';
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getListViewTemplate(): string
	{
		return '@DuoMedia/Listing/View/list.html.twig';
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getGridViewTemplate(): string
	{
		return '@DuoMedia/Listing/View/grid.html.twig';
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getDefaultView(): string
	{
		return 'grid';
	}

	/**
	 * {@inheritDoc}
	 */
	public function canSwitchView(): bool
	{
		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function defaultSorting(Request $request, QueryBuilder $builder): void
	{
		$builder->orderBy('e.name', 'ASC');
	}
}
