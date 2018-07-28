<?php

namespace Duo\FormBundle\Entity;

interface TextFormPartInterface extends FormPartInterface
{
	/**
	 * Set required
	 *
	 * @param bool $required
	 *
	 * @return TextFormPartInterface
	 */
	public function setRequired(bool $required): TextFormPartInterface;

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
	 * @return TextFormPartInterface
	 */
	public function setPlaceholder(string $placeholder = null): TextFormPartInterface;

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
	 * @return TextFormPartInterface
	 */
	public function setErrorMessage(string $errorMessage = null): TextFormPartInterface;

	/**
	 * Get errorMessage
	 *
	 * @return string
	 */
	public function getErrorMessage(): ?string;
}