<?php

namespace Duo\AdminBundle\Controller\Listing;

use Duo\AdminBundle\Configuration\Field;
use Duo\AdminBundle\Entity\Security\User;
use Duo\AdminBundle\Form\Listing\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
	/**
	 * {@inheritdoc}
	 */
	protected function getEntityClassName(): string
	{
		return User::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getFormClassName(): string
	{
		return UserType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getListType(): string
	{
		return 'user';
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
			->addField(new Field('duo.list.field.name', 'name'))
			->addField(new Field('duo.list.field.username', 'username'))
			->addField(new Field('duo.list.field.active', 'active'))
			->addField(new Field('duo.list.field.created_at', 'createdAt'))
			->addField(new Field('duo.list.field.modified_at', 'modifiedAt'));
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/", name="duo_admin_listing_user_index")
	 * @Method("GET")
	 */
	public function indexAction(Request $request): Response
	{
		return $this->doIndexAction($request);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/add", name="duo_admin_listing_user_add")
	 * @Method({"POST", "GET"})
	 */
	public function addAction(Request $request)
	{
		return $this->doAddAction($request);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/edit/{id}", name="duo_admin_listing_user_edit", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function editAction(Request $request, int $id)
	{
		return $this->doEditAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/destory/{id}", name="duo_admin_listing_user_destroy", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function destroyAction(Request $request, int $id)
	{
		return $this->doDestroyAction($request, $id);
	}
}