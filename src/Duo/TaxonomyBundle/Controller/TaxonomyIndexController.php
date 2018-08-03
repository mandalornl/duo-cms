<?php

namespace Duo\TaxonomyBundle\Controller;

use Doctrine\ORM\QueryBuilder;
use Duo\AdminBundle\Configuration\Field\Field;
use Duo\AdminBundle\Configuration\Filter\DateTimeFilter;
use Duo\AdminBundle\Configuration\Filter\StringFilter;
use Duo\AdminBundle\Controller\AbstractIndexController;
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
	 * {@inheritdoc}
	 */
	protected function defineFields(): void
	{
		$this
			->addField(new Field('name', 'duo.taxonomy.listing.field.name', 't'))
			->addField(new Field('createdAt', 'duo.taxonomy.listing.field.created_at'))
			->addField(new Field('modifiedAt', 'duo.taxonomy.listing.field.modified_at'));
	}

	/**
	 * {@inheritdoc}
	 */
	protected function defineFilters(): void
	{
		$this
			->addFilter(new StringFilter('name', 'duo.taxonomy.listing.filter.name', 't'))
			->addFilter(new DateTimeFilter('createdAt', 'duo.taxonomy.listing.filter.created'))
			->addFilter(new DateTimeFilter('modifiedAt', 'duo.taxonomy.listing.filter.modified'));
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/", name="index", methods={ "GET" })
	 */
	public function indexAction(Request $request): Response
	{
		return $this->doIndexAction($request);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function defaultSorting(Request $request, QueryBuilder $builder): void
	{
		$builder->orderBy('t.name', 'ASC');
	}
}