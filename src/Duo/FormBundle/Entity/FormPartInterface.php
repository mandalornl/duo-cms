<?php

namespace Duo\FormBundle\Entity;

interface FormPartInterface
{
	/**
	 * Get form type
	 *
	 * @return string
	 */
	public function getFormType(): string;

	/**
	 * Set label
	 *
	 * @param string $label
	 *
	 * @return FormPartInterface
	 */
	public function setLabel(string $label = null): FormPartInterface;

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
	 * @return FormPartInterface
	 */
	public function setRequired(bool $required = false): FormPartInterface;

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
	 * @return FormPartInterface
	 */
	public function setPlaceholder(string $placeholder = null): FormPartInterface;

	/**
	 * Get placeholder
	 *
	 * @return string
	 */
	public function getPlaceholder(): ?string;

	/**
	 * Set errorMessage
	 *
	 * @param string $errorMessage
	 *
	 * @return FormPartInterface
	 */
	public function setErrorMessage(string $errorMessage = null): FormPartInterface;

	/**
	 * Get errorMessage
	 *
	 * @return string
	 */
	public function getErrorMessage(): ?string;
}