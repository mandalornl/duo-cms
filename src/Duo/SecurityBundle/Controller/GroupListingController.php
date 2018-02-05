<?php

namespace Duo\SecurityBundle\Controller;

use Duo\AdminBundle\Configuration\Field;
use Duo\AdminBundle\Configuration\Filter\DateTimeFilter;
use Duo\AdminBundle\Configuration\Filter\StringFilter;
use Duo\AdminBundle\Controller\AbstractListingController;
use Duo\SecurityBundle\Entity\Group;
use Duo\SecurityBundle\Form\GroupListingType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(name="duo_security_listing_group_")
 *
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED') and has_role('ROLE_SUPER_ADMIN')")
 */
class GroupListingController extends AbstractListingController
{
	/**
	 * {@inheritdoc}
	 */
	protected function getEntityClass(): string
	{
		return Group::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getFormType(): ?string
	{
		return GroupListingType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getType(): string
	{
		return 'group';
	}

	/**
	 * Define filters
	 */
	protected function defineFilters(): void
	{
		$this
			->addFilter(new StringFilter('name', 'duo.security.listing.filter.name'))
			->addFilter(new DateTimeFilter('createdAt', 'duo.security.listing.filter.created'))
			->addFilter(new DateTimeFilter('modifiedAt', 'duo.security.listing.filter.modified'));
	}

	/**
	 * Define fields
	 */
	protected function defineFields(): void
	{
		$this
			->addField(new Field('name', 'duo.security.listing.field.name'))
			->addField(new Field('createdAt', 'duo.security.listing.field.created_at'))
			->addField(new Field('modifiedAt', 'duo.security.listing.field.modified_at'));
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