<?php

namespace Duo\TaxonomyBundle\Configuration\Field;

use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Duo\AdminBundle\Configuration\Field\AbstractField;
use Symfony\Component\HttpFoundation\Request;

class TaxonomyField extends AbstractField
{
	/**
	 * TaxonomyField constructor
	 *
	 * @param string $property
	 * @param string $label
	 * @param string $alias [optional]
	 */
	public function __construct(string $property, string $label, string $alias = 'e')
	{
		parent::__construct($property, $label, $alias);

		$this->template = '@DuoTaxonomy/Listing/Field/taxonomy.html.twig';
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildSorting(Request $request, QueryBuilder $builder, string $order): void
	{
		$alias = uniqid('e_');
		$aliasTranslation = uniqid('t_');

		$builder
			->leftJoin("{$this->alias}.{$this->property}", $alias)
			->leftJoin(
				"{$alias}.translations",
				$aliasTranslation,
				Join::WITH,
				"{$aliasTranslation}.locale = :locale"
			)
			->setParameter('locale', $request->getLocale())
			->orderBy("GROUP_CONCAT({$aliasTranslation}.name SEPARATOR '')", $order);
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildExport(Request $request, QueryBuilder $builder): void
	{
		$alias = uniqid('e_');
		$aliasTranslation = uniqid('t_');

		$builder
			->leftJoin("{$this->alias}.{$this->property}", $alias)
			->leftJoin(
				"{$alias}.translations",
				$aliasTranslation,
				Join::WITH,
				"{$aliasTranslation}.locale = :locale"
			)
			->setParameter('locale', $request->getLocale())
			->addSelect("GROUP_CONCAT({$aliasTranslation}.name) {$this->property}");
	}
}
