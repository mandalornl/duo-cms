<?php

namespace Duo\FormBundle\Controller\Listing;

use Doctrine\ORM\QueryBuilder;
use Duo\AdminBundle\Configuration\Field\Field;
use Duo\AdminBundle\Configuration\Filter\DateTimeFilter;
use Duo\AdminBundle\Configuration\Filter\StringFilter;
use Duo\AdminBundle\Controller\Listing\AbstractIndexController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/form-submission", name="duo_form_listing_submission_")
 */
class FormSubmissionIndexController extends AbstractIndexController
{
	use FormSubmissionConfigurationTrait;

	/**
	 * {@inheritDoc}
	 */
	protected function defineFields(Request $request): void
	{
		$this
			->addField(new Field('name', 'duo_form.listing.field.name'))
			->addField(new Field('locale', 'duo_form.listing.field.locale'))
			->addField(new Field('createdAt', 'duo_form.listing.field.created_at'))
			->addField(new Field('modifiedAt', 'duo_form.listing.field.modified_at'));
	}

	/**
	 * {@inheritDoc}
	 */
	protected function defineFilters(Request $request): void
	{
		$this
			->addFilter(new StringFilter('name', 'duo_form.listing.filter.name'))
			->addFilter(new StringFilter('locale', 'duo_form.listing.filter.locale'))
			->addFilter(new DateTimeFilter('createdAt', 'duo_form.listing.filter.created'))
			->addFilter(new DateTimeFilter('modifiedAt', 'duo_form.listing.filter.modified'));
	}

	/**
	 * {@inheritDoc}
	 *
	 * @Route("/", name="index", methods={ "GET" })
	 */
	public function indexAction(Request $request): Response
	{
		return $this->doIndexAction($request);
	}

	/**
	 * {@inheritDoc}
	 */
	protected function defaultSorting(Request $request, QueryBuilder $builder): void
	{
		$builder->orderBy('e.name', 'ASC');
	}
}
