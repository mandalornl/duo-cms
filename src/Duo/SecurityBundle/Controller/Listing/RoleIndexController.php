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
 * @Route("/security/role", name="duo_security_listing_role_")
 *
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
 */
class RoleIndexController extends AbstractIndexController
{
	use RoleConfigurationTrait;

	/**
	 * {@inheritDoc}
	 */
	protected function defineFields(Request $request): void
	{
		$this
			->addField(new Field('name', 'duo_security.listing.field.name'))
			->addField(new Field('createdAt', 'duo_security.listing.field.created_at'))
			->addField(new Field('modifiedAt', 'duo_security.listing.field.modified_at'));
	}

	/**
	 * {@inheritDoc}
	 */
	protected function defineFilters(Request $request): void
	{
		$this
			->addFilter(new StringFilter('name', 'duo_security.listing.filter.name'))
			->addFilter(new DateTimeFilter('createdAt', 'duo_security.listing.filter.created'))
			->addFilter(new DateTimeFilter('modifiedAt', 'duo_security.listing.filter.modified'));
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
