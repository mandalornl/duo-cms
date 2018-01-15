<?php

namespace Duo\PagePartBundle\Entity;

interface PagePartInterface
{
	/**
	 * Set value
	 *
	 * @param string $value
	 *
	 * @return PagePartInterface
	 */
	public function setValue(string $value = null): PagePartInterface;

	/**
	 * Get value
	 *
	 * @return string
	 */
	public function getValue(): ?string;

	/**
	 * Set weight
	 *
	 * @param int $weight
	 *
	 * @return PagePartInterface
	 */
	public function setWeight(int $weight): PagePartInterface;

	/**
	 * Get weight
	 *
	 * @return int
	 */
	public function getWeight(): ?int;

	/**
	 * Get form type
	 *
	 * @return string
	 */
	public function getFormType(): string;

	/**
	 * Get view
	 *
	 * @return string
	 */
	public function getView(): string;
}