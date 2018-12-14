<?php

namespace Duo\FormBundle\Entity\FormPart;

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
	 * Is required
	 *
	 * @return bool
	 */
	public function isRequired(): bool;

	/**
	 * Set placeholder
	 *
	 * @param string $placeholder
	 *
	 * @return TextFormPartInterface
	 */
	public function setPlaceholder(?string $placeholder): TextFormPartInterface;

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
	public function setErrorMessage(?string $errorMessage): TextFormPartInterface;

	/**
	 * Get errorMessage
	 *
	 * @return string
	 */
	public function getErrorMessage(): ?string;
}
