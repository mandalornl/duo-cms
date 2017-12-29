<?php

namespace Duo\AdminBundle\Entity\Behavior;

use Doctrine\Common\Collections\ArrayCollection;
use Duo\AdminBundle\Entity\Taxonomy;

interface TaxonomyInterface
{
	/**
	 * Add taxonomy
	 *
	 * @param Taxonomy $taxonomy
	 *
	 * @return TaxonomyInterface
	 */
	public function addTaxonomy(Taxonomy $taxonomy): TaxonomyInterface;

	/**
	 * Remove taxonomy
	 *
	 * @param Taxonomy $taxonomy
	 *
	 * @return TaxonomyInterface
	 */
	public function removeTaxonomy(Taxonomy $taxonomy): TaxonomyInterface;

	/**
	 * Get taxonomies
	 *
	 * @return ArrayCollection
	 */
	public function getTaxonomies();
}