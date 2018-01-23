<?php

namespace Duo\SeoBundle\Controller;

use Duo\AdminBundle\Configuration\Field;
use Duo\AdminBundle\Configuration\Filter\BooleanFilter;
use Duo\AdminBundle\Configuration\Filter\DateTimeFilter;
use Duo\AdminBundle\Configuration\Filter\StringFilter;
use Duo\AdminBundle\Controller\AbstractListingController;
use Duo\SeoBundle\Entity\Redirect;
use Duo\SeoBundle\Form\RedirectListingType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route(name="duo_seo_listing_redirect_")
 *
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED') and has_role('ROLE_ADMIN')")
 */
class RedirectListingController extends AbstractListingController
{
	/**
	 * {@inheritdoc}
	 */
	protected function getEntityClass(): string
	{
		return Redirect::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getFormType(): string
	{
		return RedirectListingType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getType(): string
	{
		return 'redirect';
	}

	/**
	 * Define filters
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
	 * Define fields
	 */
	protected function defineFields(): void
	{
		$this
			->addField(new Field('duo.seo.listing.field.origin', 'origin'))
			->addField(new Field('duo.seo.listing.field.target', 'target'))
			->addField(new Field('duo.seo.listing.field.permanent', 'permanent'))
			->addField(new Field('duo.seo.listing.field.created_at', 'createdAt'))
			->addField(new Field('duo.seo.listing.field.modified_at', 'modifiedAt'));
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

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/add", name="add")
	 * @Method({"GET", "POST"})
	 */
	public function addAction(Request $request)
	{
		return $this->doAddAction($request);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/edit/{id}", name="edit", requirements={ "id" = "\d+" })
	 * @Method({"GET", "POST"})
	 */
	public function editAction(Request $request, int $id)
	{
		return $this->doEditAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/destroy/{id}", name="destroy", requirements={ "id" = "\d+" })
	 * @Method({"GET", "POST"})
	 */
	public function destroyAction(Request $request, int $id = null)
	{
		return $this->doDestroyAction($request, $id);
	}
}