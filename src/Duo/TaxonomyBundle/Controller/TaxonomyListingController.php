<?php

namespace Duo\TaxonomyBundle\Controller;

use Duo\AdminBundle\Configuration\Field;
use Duo\AdminBundle\Configuration\Filter\DateTimeFilter;
use Duo\AdminBundle\Configuration\Filter\StringFilter;
use Duo\AdminBundle\Controller\AbstractListingController;
use Duo\TaxonomyBundle\Entity\Taxonomy;
use Duo\TaxonomyBundle\Form\TaxonomyListingType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route(name="duo_taxonomy_listing_taxonomy_")
 *
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED') and has_role('ROLE_ADMIN')")
 */
class TaxonomyListingController extends AbstractListingController
{
	/**
	 * {@inheritdoc}
	 */
	protected function getEntityClass(): string
	{
		return Taxonomy::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getFormType(): ?string
	{
		return TaxonomyListingType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getType(): string
	{
		return 'taxonomy';
	}

	/**
	 * Define filters
	 */
	protected function defineFilters(): void
	{
		$this
			->addFilter(new StringFilter('name', 'duo.taxonomy.listing.filter.name', 't'))
			->addFilter(new DateTimeFilter('createdAt', 'duo.taxonomy.listing.filter.created'))
			->addFilter(new DateTimeFilter('modifiedAt', 'duo.taxonomy.listing.filter.modified'));
	}

	/**
	 * Define fields
	 */
	protected function defineFields(): void
	{
		$this
			->addField(new Field('name', 'duo.taxonomy.listing.field.name', true, null, 't'))
			->addField(new Field('createdAt', 'duo.taxonomy.listing.field.created_at'))
			->addField(new Field('modifiedAt', 'duo.taxonomy.listing.field.modified_at'));
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
	 * @Route("/{id}", name="edit", requirements={ "id" = "\d+" })
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