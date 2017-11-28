<?php

namespace Softmedia\AdminBundle\Entity\Behavior;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Softmedia\AdminBundle\Entity\Taxonomy;

trait TaxonomyTrait
{
	/**
	 * @var Collection
	 */
	protected $taxonomies;

	/**
	 * Add taxonomy
	 *
	 * @param Taxonomy $taxonomy
	 *
	 * @return $this
	 */
	public function addTaxonomy(Taxonomy $taxonomy)
	{
		$this->getTaxonomies()->add($taxonomy);

		return $this;
	}

	/**
	 * Remove taxonomy
	 *
	 * @param Taxonomy $taxonomy
	 *
	 * @return $this
	 */
	public function removeTaxonomy(Taxonomy $taxonomy)
	{
		$this->getTaxonomies()->removeElement($taxonomy);

		return $this;
	}

	/**
	 * Get taxonomies
	 *
	 * @return ArrayCollection
	 */
	public function getTaxonomies()
	{
		return $this->taxonomies = $this->taxonomies ?: new ArrayCollection();
	}
}