<?php

namespace Duo\PageBundle\Controller;

use Duo\AdminBundle\Configuration\Field\Field;
use Duo\AdminBundle\Configuration\Filter\DateTimeFilter;
use Duo\AdminBundle\Configuration\Filter\StringFilter;
use Duo\PageBundle\Configuration\Filter\OnlineFilter;
use Duo\AdminBundle\Controller\AbstractListController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/page", name="duo_page_listing_page_")
 */
class PageListController extends AbstractListController
{
	use PageConfigurationTrait;

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
	 * Define fields
	 */
	protected function defineFields(): void
	{
		$this
			->addField(new Field('name', 'duo.page.listing.field.name'))
			->addField(new Field('title', 'duo.page.listing.field.title', true, null, 't'))
			->addField(new Field('url', 'duo.page.listing.field.url', true, '@DuoAdmin/Listing/Field/url.html.twig', 't'))
			->addField(new Field('published', 'duo.page.listing.field.online', false, '@DuoAdmin/Listing/Field/published.html.twig'))
			->addField(new Field('createdAt', 'duo.page.listing.field.created_at'))
			->addField(new Field('modifiedAt', 'duo.page.listing.field.modified_at'));
	}

	/**
	 * Define filters
	 */
	protected function defineFilters(): void
	{
		$this
			->addFilter(new StringFilter('name', 'duo.page.listing.filter.name'))
			->addFilter(new StringFilter('title', 'duo.page.listing.filter.title', 't'))
			->addFilter(new StringFilter('url', 'duo.page.listing.filter.url', 't'))
			->addFilter(new OnlineFilter('online', 'duo.page.listing.filter.online', 't'))
			->addFilter(new DateTimeFilter('createdAt', 'duo.page.listing.filter.created'))
			->addFilter(new DateTimeFilter('modifiedAt', 'duo.page.listing.filter.modified'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function isDeletable(): bool
	{
		return true;
	}
}