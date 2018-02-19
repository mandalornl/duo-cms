<?php

namespace Duo\SeoBundle\Controller;

use Duo\AdminBundle\Configuration\Field\Field;
use Duo\AdminBundle\Configuration\Filter\BooleanFilter;
use Duo\AdminBundle\Configuration\Filter\DateTimeFilter;
use Duo\AdminBundle\Configuration\Filter\StringFilter;
use Duo\AdminBundle\Controller\AbstractListController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/seo/redirect", name="duo_seo_listing_redirect_")
 */
class RedirectListController extends AbstractListController
{
	use RedirectConfigurationTrait;

	/**
	 * {@inheritdoc}
	 */
	protected function defineFields(): void
	{
		$this
			->addField(new Field('origin', 'duo.seo.listing.field.origin'))
			->addField(new Field('target', 'duo.seo.listing.field.target'))
			->addField(new Field('permanent', 'duo.seo.listing.field.permanent'))
			->addField(new Field('createdAt', 'duo.seo.listing.field.created_at'))
			->addField(new Field('modifiedAt', 'duo.seo.listing.field.modified_at'));
	}

	/**
	 * {@inheritdoc}
	 */
	protected function defineFilters(): void
	{
		$this
			->addFilter(new StringFilter('origin', 'duo.seo.listing.filter.origin'))
			->addFilter(new StringFilter('target', 'duo.seo.listing.filter.target'))
			->addFilter(new BooleanFilter('permanent', 'duo.seo.listing.filter.permanent'))
			->addFilter(new DateTimeFilter('createdAt', 'duo.seo.listing.filter.created'))
			->addFilter(new DateTimeFilter('modifiedAt', 'duo.seo.listing.filter.modified'));
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/", name="index")
	 * @Method("GET")
	 */
	public function indexAction(Request $request): Response
	{
		return $this->doIndexAction($request);
	}
}