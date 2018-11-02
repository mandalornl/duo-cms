<?php

namespace Duo\FormBundle\Entity\FormPart;

interface ChoiceFormPartInterface extends TextFormPartInterface
{
	/**
	 * Set expanded
	 *
	 * @param bool $expanded
	 *
	 * @return ChoiceFormPartInterface
	 */
	public function setExpanded(bool $expanded): ChoiceFormPartInterface;

	/**
	 * Get expanded
	 *
	 * @return bool
	 */
	public function getExpanded(): bool;

	/**
	 * Set multiple
	 *
	 * @param bool $multiple
	 *
	 * @return ChoiceFormPartInterface
	 */
	public function setMultiple(bool $multiple): ChoiceFormPartInterface;

	/**
	 * Get multiple
	 *
	 * @return bool
	 */
	public function getMultiple(): bool;

	/**
	 * Set choices
	 *
	 * @param string $choices
	 *
	 * @return ChoiceFormPartInterface
	 */
	public function setChoices(?string $choices): ChoiceFormPartInterface;

	/**
	 * Get choices
	 *
	 * @return string
	 */
	public function getChoices(): ?string;
}