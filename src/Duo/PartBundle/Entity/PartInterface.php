<?php

namespace Duo\PartBundle\Entity;

interface PartInterface
{
	/**
	 * Set value
	 *
	 * @param string $value
	 *
	 * @return PartInterface
	 */
	public function setValue(string $value = null): PartInterface;

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
	 * @return PartInterface
	 */
	public function setWeight(int $weight): PartInterface;

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