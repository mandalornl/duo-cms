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
	 * Is expanded
	 *
	 * @return bool
	 */
	public function isExpanded(): bool;

	/**
	 * Set multiple
	 *
	 * @param bool $multiple
	 *
	 * @return ChoiceFormPartInterface
	 */
	public function setMultiple(bool $multiple): ChoiceFormPartInterface;

	/**
	 * Is multiple
	 *
	 * @return bool
	 */
	public function isMultiple(): bool;

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
