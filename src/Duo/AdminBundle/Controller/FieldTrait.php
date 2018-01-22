<?php

namespace Duo\AdminBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Duo\AdminBundle\Configuration\FieldInterface;

trait FieldTrait
{
	/**
	 * @var ArrayCollection
	 */
	protected $fields;

	/**
	 * Add field
	 *
	 * @param FieldInterface $field
	 *
	 * @return $this
	 */
	public function addField(FieldInterface $field)
	{
		$this->getFields()->add($field);

		return $this;
	}

	/**
	 * Remove field
	 *
	 * @param FieldInterface $field
	 *
	 * @return $this
	 */
	public function removeField(FieldInterface $field)
	{
		$this->getFields()->removeElement($field);

		return $this;
	}

	/**
	 * Get fields
	 *
	 * @return ArrayCollection
	 */
	public function getFields(): ArrayCollection
	{
		return $this->fields = $this->fields ?: new ArrayCollection();
	}

	/**
	 * Define fields
	 */
	abstract protected function defineFields(): void;
}