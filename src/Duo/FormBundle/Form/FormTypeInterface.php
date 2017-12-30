<?php

namespace Duo\FormBundle\Form;

interface FormTypeInterface
{
	/**
	 * Set type
	 *
	 * @param string $type
	 *
	 * @return FormTypeInterface
	 */
	public function setType(string $type = null): FormTypeInterface;

	/**
	 * @return string
	 */
	public function getType(): ?string;

	/**
	 * Set label
	 *
	 * @param string $label
	 *
	 * @return FormTypeInterface
	 */
	public function setLabel(string $label = null): FormTypeInterface;

	/**
	 * Get label
	 *
	 * @return string
	 */
	public function getLabel(): ?string;

	/**
	 * Set required
	 *
	 * @param bool $required
	 *
	 * @return FormTypeInterface
	 */
	public function setRequired(bool $required = false): FormTypeInterface;

	/**
	 * Get required
	 *
	 * @return bool
	 */
	public function getRequired(): bool;

	/**
	 * Set placeholder
	 *
	 * @param string $placeholder
	 *
	 * @return FormTypeInterface
	 */
	public function setPlaceholder(string $placeholder = null): FormTypeInterface;

	/**
	 * Get placeholder
	 *
	 * @return string
	 */
	public function getPlaceHolder(): ?string;

	/**
	 * Set errorMessage
	 *
	 * @param string $errorMessage
	 *
	 * @return FormTypeInterface
	 */
	public function setErrorMessage(string $errorMessage = null): FormTypeInterface;

	/**
	 * Get errorMessage
	 *
	 * @return string
	 */
	public function getErrorMessage(): ?string;
}