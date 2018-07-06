<?php

namespace Duo\TaxonomyBundle\Controller;

use Duo\TaxonomyBundle\Entity\Taxonomy;
use Duo\TaxonomyBundle\Form\TaxonomyType;

trait TaxonomyConfigurationTrait
{
	/**
	 * {@inheritdoc}
	 */
	protected function getEntityClass(): string
	{
		return Taxonomy::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getFormType(): ?string
	{
		return TaxonomyType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getType(): string
	{
		return 'taxonomy';
	}
}