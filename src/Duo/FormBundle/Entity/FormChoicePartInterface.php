<?php

namespace Duo\FormBundle\Entity;

interface FormChoicePartInterface
{
	/**
	 * Set expanded
	 *
	 * @param bool $expanded
	 *
	 * @return FormChoicePartInterface
	 */
	public function setExpanded(bool $expanded = false): FormChoicePartInterface;

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
	 * @return FormChoicePartInterface
	 */
	public function setMultiple(bool $multiple = false): FormChoicePartInterface;

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
	 * @return FormChoicePartInterface
	 */
	public function setChoices(string $choices = null): FormChoicePartInterface;

	/**
	 * Get choices
	 *
	 * @return string
	 */
	public function getChoices(): ?string;
}