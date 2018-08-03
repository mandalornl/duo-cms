<?php

namespace Duo\TranslatorBundle\Controller;

use Doctrine\ORM\QueryBuilder;
use Duo\AdminBundle\Configuration\Field\Field;
use Duo\AdminBundle\Configuration\Filter\DateTimeFilter;
use Duo\AdminBundle\Configuration\Filter\StringFilter;
use Duo\AdminBundle\Controller\AbstractIndexController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/settings/translation", name="duo_translator_listing_entry_")
 */
class EntryIndexController extends AbstractIndexController
{
	use EntryConfigurationTrait;

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
	protected function defineFields(): void
	{
		$this
			->addField(new Field('keyword', 'duo.translator.listing.field.keyword'))
			->addField(new Field('domain', 'duo.translator.listing.field.domain'))
			->addField(new Field('createdAt', 'duo.translator.listing.field.created_at'))
			->addField(new Field('modifiedAt', 'duo.translator.listing.field.modified_at'));
	}

	/**
	 * {@inheritdoc}
	 */
	protected function defineFilters(): void
	{
		$this
			->addFilter(new StringFilter('keyword', 'duo.translator.listing.filter.keyword'))
			->addFilter(new StringFilter('domain', 'duo.translator.listing.filter.domain'))
			->addFilter(new DateTimeFilter('createdAt', 'duo.translator.listing.filter.created'))
			->addFilter(new DateTimeFilter('modifiedAt', 'duo.translator.listing.filter.modified'));
	}

	/**
	 * {@inheritdoc}
	 */
	protected function defaultSorting(Request $request, QueryBuilder $builder): void
	{
		$builder->orderBy('e.keyword', 'ASC');
	}
}