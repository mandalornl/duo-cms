<?php

namespace Duo\AdminBundle\Controller\Listing\Modules;

use Duo\AdminBundle\Configuration\Field;
use Duo\AdminBundle\Controller\Listing\AbstractController;
use Duo\AdminBundle\Entity\Taxonomy;
use Duo\AdminBundle\Form\Listing\TaxonomyType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaxonomyController extends AbstractController
{
	/**
	 * {@inheritdoc}
	 */
	protected function getEntityClassName(): string
	{
		return Taxonomy::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getFormClassName(): string
	{
		return TaxonomyType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getListType(): string
	{
		return 'taxonomy';
	}

	/**
	 * {@inheritdoc}
	 */
	protected function defineFilters(): void
	{
		// TODO: Implement defineFilters() method.
	}

	/**
	 * {@inheritdoc}
	 */
	protected function defineFields(): void
	{
		$this
			->addField(new Field('duo.admin.listing.field.name', 'name'))
			->addField(new Field('duo.admin.listing.field.created_at', 'createdAt'))
			->addField(new Field('duo.admin.listing.field.modified_at', 'modifiedAt'));
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/", name="duo_admin_listing_taxonomy_index")
	 * @Method("GET")
	 */
	public function indexAction(Request $request): Response
	{
		return $this->doIndexAction($request);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/add", name="duo_admin_listing_taxonomy_add")
	 * @Method({"POST", "GET"})
	 */
	public function addAction(Request $request)
	{
		return $this->doAddAction($request);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/{id}", name="duo_admin_listing_taxonomy_edit", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function editAction(Request $request, int $id)
	{
		return $this->doEditAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/destroy/{id}", name="duo_admin_listing_taxonomy_destroy", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function destroyAction(Request $request, int $id)
	{
		return $this->doDestroyAction($request, $id);
	}
}