<?php

namespace Duo\CoreBundle\Entity\Property;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

interface TranslateInterface
{
	/**
	 * Set current locale
	 *
	 * @param string $currentLocale
	 *
	 * @return TranslateInterface
	 */
	public function setCurrentLocale(string $currentLocale): TranslateInterface;

	/**
	 * Get current locale
	 *
	 * @return string
	 */
	public function getCurrentLocale(): string;

	/**
	 * Set default locale
	 *
	 * @param string $defaultLocale
	 *
	 * @return TranslateInterface
	 */
	public function setDefaultLocale(string $defaultLocale): TranslateInterface;

	/**
	 * Get default locale
	 *
	 * @return string
	 */
	public function getDefaultLocale(): string;

	/**
	 * Add translation
	 *
	 * @param TranslationInterface $translation
	 *
	 * @return TranslateInterface
	 */
	public function addTranslation(TranslationInterface $translation): TranslateInterface;

	/**
	 * Remove translation
	 *
	 * @param TranslationInterface $translation
	 *
	 * @return TranslateInterface
	 */
	public function removeTranslation(TranslationInterface $translation): TranslateInterface;

	/**
	 * Get translations
	 *
	 * @return ArrayCollection
	 */
	public function getTranslations(): Collection;

	/**
	 * Add new translation
	 *
	 * @param TranslationInterface $translation
	 *
	 * @return TranslateInterface
	 */
	public function addNewTranslation(TranslationInterface $translation): TranslateInterface;

	/**
	 * Remove new translation
	 *
	 * @param TranslationInterface $translation
	 *
	 * @return TranslateInterface
	 */
	public function removeNewTranslation(TranslationInterface $translation): TranslateInterface;

	/**
	 * Get new translations
	 *
	 * @return ArrayCollection
	 */
	public function getNewTranslations(): Collection;

	/**
	 * Merge new translations
	 *
	 * @return TranslateInterface
	 */
	public function mergeNewTranslations(): TranslateInterface;

	/**
	 * Translate
	 *
	 * @param string $locale [optional]
	 * @param bool $fallback [optional]
	 *
	 * @return object
	 */
	public function translate(string $locale = null, bool $fallback = true);
}