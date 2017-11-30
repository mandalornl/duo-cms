<?php

namespace Softmedia\AdminBundle\Configuration;

interface FieldInterface
{
	/**
	 * Set title
	 *
	 * @param string $title
	 *
	 * @return FieldInterface
	 */
	public function setTitle(string $title): FieldInterface;

	/**
	 * Get title
	 *
	 * @return string
	 */
	public function getTitle(): string;

	/**
	 * Set property
	 *
	 * @param string $property
	 *
	 * @return FieldInterface
	 */
	public function setProperty(string $property): FieldInterface;

	/**
	 * Get property
	 *
	 * @return string
	 */
	public function getProperty(): string;

	/**
	 * Set sortable
	 *
	 * @param bool $sortable
	 *
	 * @return FieldInterface
	 */
	public function setSortable(bool $sortable): FieldInterface;

	/**
	 * Get sortable
	 *
	 * @return bool
	 */
	public function getSortable(): bool;
};