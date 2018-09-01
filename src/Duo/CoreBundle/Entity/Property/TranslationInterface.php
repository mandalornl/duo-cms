<?php

namespace Duo\CoreBundle\Entity\Property;

interface TranslationInterface extends IdInterface
{
	/**
	 * Set translatable
	 *
	 * @param TranslateInterface $translatable
	 *
	 * @return TranslationInterface
	 */
	public function setTranslatable(TranslateInterface $translatable = null): TranslationInterface;

	/**
	 * Get translatable
	 *
	 * @return TranslateInterface
	 */
	public function getTranslatable(): TranslateInterface;

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