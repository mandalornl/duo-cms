<?php

namespace Duo\TaxonomyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

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