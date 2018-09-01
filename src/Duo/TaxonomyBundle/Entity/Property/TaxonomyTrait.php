<?php

namespace Duo\TaxonomyBundle\Entity\Property;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Duo\TaxonomyBundle\Entity\Taxonomy;

trait TaxonomyTrait
{
	/**
	 * @var Collection
	 */
	protected $taxonomies;

	/**
	 * {@inheritdoc}
	 */
	public function addTaxonomy(Taxonomy $taxonomy): TaxonomyInterface
	{
		$this->getTaxonomies()->add($taxonomy);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function removeTaxonomy(Taxonomy $taxonomy): TaxonomyInterface
	{
		$this->getTaxonomies()->removeElement($taxonomy);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTaxonomies(): Collection
	{
		return $this->taxonomies = $this->taxonomies ?: new ArrayCollection();
	}
}