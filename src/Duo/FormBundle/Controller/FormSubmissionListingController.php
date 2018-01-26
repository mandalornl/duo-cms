<?php

namespace Duo\FormBundle\Controller;

use Duo\AdminBundle\Configuration\Field;
use Duo\AdminBundle\Configuration\Filter\DateTimeFilter;
use Duo\AdminBundle\Configuration\Filter\StringFilter;
use Duo\AdminBundle\Controller\AbstractListingController;
use Duo\FormBundle\Entity\FormSubmission;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route(name="duo_form_listing_submission_")
 */
class FormSubmissionListingController extends AbstractListingController
{
	/**
	 * {@inheritdoc}
	 */
	protected function getEntityClass(): string
	{
		return FormSubmission::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getFormType(): ?string
	{
		return null;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getType(): string
	{
		return 'form_submission';
	}

	/**
	 * {@inheritdoc}
	 */
	protected function defineFields(): void
	{
		$this
			->addField(new Field('name', 'duo.form.listing.field.name'))
			->addField(new Field('locale', 'duo.form.listing.field.locale'))
			->addField(new Field('createdAt', 'duo.form.listing.field.created_at'))
			->addField(new Field('modifiedAt', 'duo.form.listing.field.modified_at'));
	}

	/**
	 * {@inheritdoc}
	 */
	protected function defineFilters(): void
	{
		$this
			->addFilter(new StringFilter('name', 'duo.form.listing.filter.name'))
			->addFilter(new StringFilter('locale', 'duo.form.listing.filter.locale'))
			->addFilter(new DateTimeFilter('createdAt', 'duo.form.listing.filter.created'))
			->addFilter(new DateTimeFilter('modifiedAt', 'duo.form.listing.filter.modified'));
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getEditTemplate(): string
	{
		return '@DuoForm/Listing/edit.html.twig';
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
	 */
	public function addAction(Request $request) {}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/{id}", name="edit", requirements={ "id" = "\d+" })
	 * @Method("GET")
	 */
	public function editAction(Request $request, int $id)
	{
		$entity = $this->getDoctrine()->getRepository($this->getEntityClass())->find($id);

		if ($entity === null)
		{
			return $this->entityNotFound($request, $id);
		}

		return $this->render($this->getEditTemplate(), (array)$this->getDefaultContext([
			'entity' => $entity
		]));
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/destroy/{id}", name="destroy", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function destroyAction(Request $request, int $id = null)
	{
		return $this->doDestroyAction($request, $id);
	}
}