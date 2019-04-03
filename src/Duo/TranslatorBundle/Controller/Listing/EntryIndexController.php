<?php

namespace Duo\TranslatorBundle\Controller\Listing;

use Doctrine\ORM\QueryBuilder;
use Duo\AdminBundle\Configuration\Action\Action;
use Duo\AdminBundle\Configuration\Field\Field;
use Duo\AdminBundle\Configuration\Filter\DateTimeFilter;
use Duo\AdminBundle\Configuration\Filter\StringFilter;
use Duo\AdminBundle\Controller\Listing\AbstractIndexController;
use Duo\TranslatorBundle\Entity\Entry;
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
	 * {@inheritDoc}
	 *
	 * @Route("/", name="index", methods={ "GET" })
	 */
	public function indexAction(Request $request): Response
	{
		$count = $this->getDoctrine()->getRepository(Entry::class)->count([
			'flag' => [
				Entry::FLAG_NEW,
				Entry::FLAG_UPDATED
			]
		]);

		if ($count)
		{
			$this->addFlash('info', $this->get('translator')->trans('duo_translator.not_live', [], 'flashes'));
		}

		return $this->doIndexAction($request);
	}

	/**
	 * {@inheritDoc}
	 */
	protected function defineFields(Request $request): void
	{
		$this
			->addField(new Field('keyword', 'duo_translator.listing.field.keyword'))
			->addField(new Field('domain', 'duo_translator.listing.field.domain'))
			->addField(new Field('createdAt', 'duo_translator.listing.field.created_at'))
			->addField(new Field('modifiedAt', 'duo_translator.listing.field.modified_at'));
	}

	/**
	 * {@inheritDoc}
	 */
	protected function defineFilters(Request $request): void
	{
		$this
			->addFilter(new StringFilter('keyword', 'duo_translator.listing.filter.keyword'))
			->addFilter(new StringFilter('domain', 'duo_translator.listing.filter.domain'))
			->addFilter(new DateTimeFilter('createdAt', 'duo_translator.listing.filter.created'))
			->addFilter(new DateTimeFilter('modifiedAt', 'duo_translator.listing.filter.modified'));
	}

	/**
	 * {@inheritDoc}
	 */
	protected function defineActions(Request $request): void
	{
		$this->addAction(
			(new Action('duo_translator.toolbar.actions.reload'))
				->setRoute('duo_translator_reload')
		);
	}

	/**
	 * {@inheritDoc}
	 */
	protected function defaultSorting(Request $request, QueryBuilder $builder): void
	{
		$builder->orderBy('e.keyword', 'ASC');
	}
}
