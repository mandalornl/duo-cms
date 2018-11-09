<?php

namespace Duo\PageBundle\Controller\Listing;

use Doctrine\ORM\QueryBuilder;
use Duo\AdminBundle\Configuration\Field\Field;
use Duo\AdminBundle\Configuration\Filter\DateTimeFilter;
use Duo\AdminBundle\Configuration\Filter\StringFilter;
use Duo\PageBundle\Configuration\Filter\OnlineFilter;
use Duo\AdminBundle\Controller\Listing\AbstractIndexController;
use Duo\TaxonomyBundle\Configuration\Filter\TaxonomyFilter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/page", name="duo_page_listing_page_")
 */
class PageIndexController extends AbstractIndexController
{
	use PageConfigurationTrait;

	/**
	 * {@inheritdoc}
	 */
	protected function defineFields(): void
	{
		$this
			->addField(new Field('name', 'duo.page.listing.field.name'))
			->addField(new Field('title', 'duo.page.listing.field.title', 't'))
			->addField(
				(new Field('url', 'duo.page.listing.field.url', 't'))
					->setTemplate('@DuoAdmin/Listing/Field/url.html.twig')
			)
			->addField(
				(new Field('published', 'duo.page.listing.field.online'))
					->setSortable(false)
					->setTemplate('@DuoAdmin/Listing/Field/published.html.twig')
			)
			->addField(new Field('createdAt', 'duo.page.listing.field.created_at'))
			->addField(new Field('modifiedAt', 'duo.page.listing.field.modified_at'));
	}

	/**
	 * {@inheritdoc}
	 */
	protected function defineFilters(): void
	{
		$this
			->addFilter(new StringFilter('name', 'duo.page.listing.filter.name'))
			->addFilter(new StringFilter('title', 'duo.page.listing.filter.title', 't'))
			->addFilter(new StringFilter('url', 'duo.page.listing.filter.url', 't'))
			->addFilter(new OnlineFilter('online', 'duo.page.listing.filter.online', 't'))
			->addFilter(new TaxonomyFilter('id', 'duo.page.listing.filter.taxonomies'))
			->addFilter(new DateTimeFilter('createdAt', 'duo.page.listing.filter.created'))
			->addFilter(new DateTimeFilter('modifiedAt', 'duo.page.listing.filter.modified'));
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
		$builder->orderBy('t.title', 'ASC');
	}
}