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
}