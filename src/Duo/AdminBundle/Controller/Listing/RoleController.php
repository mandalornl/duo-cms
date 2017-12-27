<?php

namespace Duo\AdminBundle\Controller\Listing;

use Duo\AdminBundle\Configuration\Field;
use Duo\AdminBundle\Entity\Security\Role;
use Duo\AdminBundle\Form\Listing\RoleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RoleController extends AbstractController
{
	/**
	 * {@inheritdoc}
	 */
	protected function getEntityClassName(): string
	{
		return Role::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getFormClassName(): string
	{
		return RoleType::class;
	}

	/**
	 * Get route prefix
	 *
	 * @return string
	 */
	protected function getListType(): string
	{
		return 'role';
	}

	/**
	 * Define filters
	 */
	protected function defineFilters(): void
	{
		// TODO: Implement defineFilters() method.
	}

	/**
	 * Define fields
	 */
	protected function defineFields(): void
	{
		$this
			->addField(new Field('duo.list.field.name', 'name'))
			->addField(new Field('duo.list.field.created_at', 'createdAt'))
			->addField(new Field('duo.list.field.modified_at', 'modifiedAt'));
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/", name="duo_admin_listing_role_index")
	 * @Method("GET")
	 */
	public function indexAction(Request $request): Response
	{
		return $this->doIndexAction($request);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/add", name="duo_admin_listing_role_add")
	 * @Method({"POST", "GET"})
	 */
	public function addAction(Request $request)
	{
		return $this->doAddAction($request);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/edit/{id}", name="duo_admin_listing_role_edit", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function editAction(Request $request, int $id)
	{
		return $this->doEditAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/destroy/{id}", name="duo_admin_listing_role_destroy", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function destroyAction(Request $request, int $id)
	{
		return $this->doDestroyAction($request, $id);
	}
}