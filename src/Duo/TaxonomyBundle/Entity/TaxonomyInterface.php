<?php

namespace Duo\TaxonomyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

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