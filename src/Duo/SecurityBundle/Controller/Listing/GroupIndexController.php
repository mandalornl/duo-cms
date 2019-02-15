<?php

namespace Duo\SecurityBundle\Controller\Listing;

use Doctrine\ORM\QueryBuilder;
use Duo\AdminBundle\Configuration\Field\Field;
use Duo\AdminBundle\Configuration\Filter\DateTimeFilter;
use Duo\AdminBundle\Configuration\Filter\StringFilter;
use Duo\AdminBundle\Controller\Listing\AbstractIndexController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/security/group", name="duo_security_listing_group_")
 *
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED') and has_role('ROLE_SUPER_ADMIN')")
 */
class GroupIndexController extends AbstractIndexController
{
	use GroupConfigurationTrait;

	/**
	 * {@inheritdoc}
	 */
	protected function defineFields(Request $request): void
	{
		$this
			->addField(new Field('name', 'duo_security.listing.field.name'))
			->addField(new Field('createdAt', 'duo_security.listing.field.created_at'))
			->addField(new Field('modifiedAt', 'duo_security.listing.field.modified_at'));
	}

	/**
	 * {@inheritdoc}
	 */
	protected function defineFilters(Request $request): void
	{
		$this
			->addFilter(new StringFilter('name', 'duo_security.listing.filter.name'))
			->addFilter(new DateTimeFilter('createdAt', 'duo_security.listing.filter.created'))
			->addFilter(new DateTimeFilter('modifiedAt', 'duo_security.listing.filter.modified'));
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
