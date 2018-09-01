<?php

namespace Duo\TaxonomyBundle\Entity\Property;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Duo\TaxonomyBundle\Entity\Taxonomy;

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
	public function getTaxonomies(): Collection;
}