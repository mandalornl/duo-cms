<?php

namespace Duo\TranslatorBundle\Controller\Listing;

use Doctrine\ORM\QueryBuilder;
use Duo\AdminBundle\Configuration\Action\ListAction;
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
	 * {@inheritdoc}
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
			$this->addFlash('info', $this->get('translator')->trans('duo.translator.not_live', [], 'flashes'));
		}

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
	protected function defineListActions(): void
	{
		$this->addListAction(new ListAction('duo.translator.toolbar.actions.reload', 'duo_translator_reload'));
	}

	/**
	 * {@inheritdoc}
	 */
	protected function defaultSorting(Request $request, QueryBuilder $builder): void
	{
		$builder->orderBy('e.keyword', 'ASC');
	}
}