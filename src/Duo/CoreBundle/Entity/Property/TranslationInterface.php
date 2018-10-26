<?php

namespace Duo\CoreBundle\Entity\Property;

interface TranslationInterface extends IdInterface
{
	/**
	 * Set entity
	 *
	 * @param TranslateInterface $entity
	 *
	 * @return TranslationInterface
	 */
	public function setEntity(?TranslateInterface $entity): TranslationInterface;

	/**
	 * Get entity
	 *
	 * @return TranslateInterface
	 */
	public function getEntity(): ?TranslateInterface;

	/**
	 * Set locale
	 *
	 * @param string $locale
	 *
	 * @return TranslationInterface
	 */
	public function setLocale(string $locale): TranslationInterface;

	/**
	 * Get locale
	 *
	 * @return string
	 */
	public function getLocale(): ?string;
}