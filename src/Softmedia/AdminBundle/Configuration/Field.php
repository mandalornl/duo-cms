<?php

namespace Softmedia\AdminBundle\Configuration;

class Field implements FieldInterface
{
	/**
	 * @var string
	 */
	private $name;

	/**
	 * {@inheritdoc}
	 */
	public function setName(string $name = null): FieldInterface
	{
		$this->name = $name;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName(): ?string
	{
		return $this->name;
	}
}