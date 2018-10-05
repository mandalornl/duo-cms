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
 * @Route("/form", name="duo_form_listing_form_")
 */
class FormIndexController extends AbstractIndexController
{
	use FormConfigurationTrait;

	/**
	 * {@inheritdoc}
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
	 */
	protected function defineFilters(): void
	{
		$this
			->addFilter(new StringFilter('name', 'duo.form.listing.filter.name'))
			->addFilter(new DateTimeFilter('createdAt', 'duo.form.listing.filter.created'))
			->addFilter(new DateTimeFilter('modifiedAt', 'duo.form.listing.filter.modified'));
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/", name="index", methods={ "GET" })
	 */
	public function indexAction(Request $request): Response
	{
		return $this->doIndexAction($request);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function defaultSorting(Request $request, QueryBuilder $builder): void
	{
		$builder->orderBy('e.name', 'ASC');
	}
}