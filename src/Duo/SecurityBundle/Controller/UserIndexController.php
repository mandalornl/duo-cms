<?php

namespace Duo\SecurityBundle\Controller;

use Doctrine\ORM\QueryBuilder;
use Duo\AdminBundle\Configuration\Field\Field;
use Duo\AdminBundle\Configuration\Filter\BooleanFilter;
use Duo\AdminBundle\Configuration\Filter\DateTimeFilter;
use Duo\AdminBundle\Configuration\Filter\StringFilter;
use Duo\AdminBundle\Controller\AbstractIndexController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/security/user", name="duo_security_listing_user_")
 *
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED') and has_role('ROLE_SUPER_ADMIN')")
 */
class UserIndexController extends AbstractIndexController
{
	use UserConfigurationTrait;

	/**
	 * {@inheritdoc}
	 */
	protected function defineFields(): void
	{
		$this
			->addField(new Field('name', 'duo.security.listing.field.name'))
			->addField(new Field('username', 'duo.security.listing.field.username'))
			->addField(new Field('active', 'duo.security.listing.field.active'))
			->addField(new Field('createdAt', 'duo.security.listing.field.created_at'))
			->addField(new Field('modifiedAt', 'duo.security.listing.field.modified_at'));
	}

	/**
	 * {@inheritdoc}
	 */
	protected function defineFilters(): void
	{
		$this
			->addFilter(new StringFilter('name', 'duo.security.listing.filter.name'))
			->addFilter(new StringFilter('username', 'duo.security.listing.filter.username'))
			->addFilter(new BooleanFilter('active', 'duo.security.listing.filter.active'))
			->addFilter(new DateTimeFilter('createdAt', 'duo.security.listing.filter.created'))
			->addFilter(new DateTimeFilter('modifiedAt', 'duo.security.listing.filter.modified'));
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
	protected function defaultSorting(QueryBuilder $builder): void
	{
		$builder->orderBy('e.name', 'ASC');
	}
}