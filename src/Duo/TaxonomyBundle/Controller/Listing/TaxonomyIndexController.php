<?php

namespace Duo\TaxonomyBundle\Controller\Listing;

use Doctrine\ORM\QueryBuilder;
use Duo\AdminBundle\Configuration\Field\Field;
use Duo\AdminBundle\Configuration\Filter\DateTimeFilter;
use Duo\AdminBundle\Configuration\Filter\StringFilter;
use Duo\AdminBundle\Controller\Listing\AbstractIndexController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/module/taxonomy", name="duo_taxonomy_listing_taxonomy_")
 */
class TaxonomyIndexController extends AbstractIndexController
{
	use TaxonomyConfigurationTrait;

	/**
	 * {@inheritDoc}
	 */
	protected function defineFields(Request $request): void
	{
		$this
			->addField(new Field('name', 'duo_taxonomy.listing.field.name', 't'))
			->addField(new Field('createdAt', 'duo_taxonomy.listing.field.created_at'))
			->addField(new Field('modifiedAt', 'duo_taxonomy.listing.field.modified_at'));
	}

	/**
	 * {@inheritDoc}
	 */
	protected function defineFilters(Request $request): void
	{
		$this
			->addFilter(new StringFilter('name', 'duo_taxonomy.listing.filter.name', 't'))
			->addFilter(new DateTimeFilter('createdAt', 'duo_taxonomy.listing.filter.created'))
			->addFilter(new DateTimeFilter('modifiedAt', 'duo_taxonomy.listing.filter.modified'));
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
	protected function defaultSorting(Request $request, QueryBuilder $builder): void
	{
		$builder->orderBy('t.name', 'ASC');
	}
}
