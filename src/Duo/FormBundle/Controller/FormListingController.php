<?php

namespace Duo\FormBundle\Controller;

use Duo\AdminBundle\Configuration\Field;
use Duo\AdminBundle\Configuration\Filter\DateTimeFilter;
use Duo\AdminBundle\Configuration\Filter\StringFilter;
use Duo\AdminBundle\Controller\AbstractListingController;
use Duo\BehaviorBundle\Controller\DuplicateTrait;
use Duo\BehaviorBundle\Entity\DuplicateInterface;
use Duo\FormBundle\Entity\Form;
use Duo\FormBundle\Form\FormListingType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route(name="duo_form_listing_form_")
 *
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED') and has_role('ROLE_ADMIN')")
 */
class FormListingController extends AbstractListingController implements DuplicateInterface
{
	use DuplicateTrait;

	/**
	 * {@inheritdoc}
	 */
	protected function getEntityClass(): string
	{
		return Form::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getFormType(): ?string
	{
		return FormListingType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getType(): string
	{
		return 'form';
	}

	/**
	 * Define filters
	 */
	protected function defineFilters(): void
	{
		$this
			->addFilter(new StringFilter('name', 'duo.form.listing.filter.name'))
			->addFilter(new DateTimeFilter('createdAt', 'duo.form.listing.filter.created'))
			->addFilter(new DateTimeFilter('modifiedAt', 'duo.form.listing.filter.modified'));
	}

	/**
	 * Define fields
	 */
	protected function defineFields(): void
	{
		$this
			->addField(new Field('name', 'duo.form.listing.field.name'))
			->addField(new Field('createdAt', 'duo.form.listing.field.created_at'))
			->addField(new Field('modifiedAt', 'duo.form.listing.field.modified_at'));
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

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/duplicate/{id}", name="duplicate", requirements={ "id" = "\d+" })
	 * @Method({"GET", "POST"})
	 */
	public function duplicateAction(Request $request, int $id)
	{
		return $this->doDuplicateAction($request, $id);
	}
}