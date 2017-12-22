<?php

namespace Duo\AdminBundle\Entity\Behavior;

use Doctrine\Common\Collections\ArrayCollection;

interface TranslatableInterface
{
	/**
	 * Set current locale
	 *
	 * @param string $currentLocale
	 *
	 * @return TranslatableInterface
	 */
	public function setCurrentLocale(string $currentLocale): TranslatableInterface;

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
	 * @return TranslatableInterface
	 */
	public function setDefaultLocale(string $defaultLocale): TranslatableInterface;

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
	 * @return TranslatableInterface
	 */
	public function addTranslation(TranslationInterface $translation): TranslatableInterface;

	/**
	 * Remove translation
	 *
	 * @param TranslationInterface $translation
	 *
	 * @return TranslatableInterface
	 */
	public function removeTranslation(TranslationInterface $translation): TranslatableInterface;

	/**
	 * Get translations
	 *
	 * @return ArrayCollection
	 */
	public function getTranslations();

	/**
	 * Add new translation
	 *
	 * @param TranslationInterface $translation
	 *
	 * @return TranslatableInterface
	 */
	public function addNewTranslation(TranslationInterface $translation): TranslatableInterface;

	/**
	 * Remove new translation
	 *
	 * @param TranslationInterface $translation
	 *
	 * @return TranslatableInterface
	 */
	public function removeNewTranslation(TranslationInterface $translation): TranslatableInterface;

	/**
	 * Get new translations
	 *
	 * @return ArrayCollection
	 */
	public function getNewTranslations();

	/**
	 * Merge new translations
	 *
	 * @return TranslatableInterface
	 */
	public function mergeNewTranslations(): TranslatableInterface;

	/**
	 * Translate
	 *
	 * @param string $locale [optional]
	 * @param bool $fallback [optional]
	 *
	 * @return mixed
	 */
	public function translate(string $locale = null, bool $fallback = true);
}