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
	 * @param TranslateInterface $translatable
	 *
	 * @return TranslationInterface
	 */
	public function setTranslatable(TranslateInterface $translatable): TranslationInterface;

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