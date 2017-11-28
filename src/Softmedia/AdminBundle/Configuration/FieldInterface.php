<?php

namespace Softmedia\AdminBundle\Configuration;

interface FieldInterface
{
	/**
	 * Set name
	 *
	 * @param string $name
	 *
	 * @return FieldInterface
	 */
	public function setName(string $name = null): FieldInterface;

	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName(): ?string;
};