<?php

namespace Duo\FormBundle\Form;

interface FormChoiceTypeInterface extends FormTypeInterface
{
	/**
	 * Set expanded
	 *
	 * @param bool $expanded
	 *
	 * @return FormChoiceTypeInterface
	 */
	public function setExpanded(bool $expanded = false): FormChoiceTypeInterface;

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
	 * @return FormChoiceTypeInterface
	 */
	public function setMultiple(bool $multiple = false): FormChoiceTypeInterface;

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
	 * @return FormChoiceTypeInterface
	 */
	public function setChoices(string $choices = null): FormChoiceTypeInterface;

	/**
	 * Get choices
	 *
	 * @return string
	 */
	public function getChoices(): ?string;

	/**
	 * Set emptyValue
	 *
	 * @param string $emptyValue
	 *
	 * @return FormChoiceTypeInterface
	 */
	public function setEmptyValue(string $emptyValue = null): FormChoiceTypeInterface;

	/**
	 * Get emptyValue
	 *
	 * @return string
	 */
	public function getEmptyValue(): ?string;
}