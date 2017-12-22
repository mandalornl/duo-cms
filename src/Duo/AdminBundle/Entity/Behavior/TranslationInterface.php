<?php

namespace Duo\AdminBundle\Entity\Behavior;

interface TranslationInterface
{
	/**
	 * Get id
	 *
	 * @return int
	 */
	public function getId(): int;

	/**
	 * Set translatable
	 *
	 * @param TranslatableInterface $translatable
	 *
	 * @return TranslationInterface
	 */
	public function setTranslatable(TranslatableInterface $translatable): TranslationInterface;

	/**
	 * Get translatable
	 *
	 * @return TranslatableInterface
	 */
	public function getTranslatable(): TranslatableInterface;

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