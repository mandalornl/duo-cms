<?php

namespace Duo\CoreBundle\Entity\Property;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

trait TranslateTrait
{
	/**
	 * @var Collection
	 */
	protected $translations;

	/**
	 * @var Collection
	 */
	protected $newTranslations;

	/**
	 * @var string
	 */
	protected $currentLocale = 'nl';

	/**
	 * @var string
	 */
	protected $defaultLocale = 'nl';

	/**
	 * {@inheritdoc}
	 *
	 * @throws \Exception
	 */
	public function __call(string $name, array $arguments = [])
	{
		$entity = $this->translate($this->currentLocale, false);

		$reflectionClass = new \ReflectionClass($entity);

		// access property
		if ($reflectionClass->hasProperty($name))
		{
			$property = $reflectionClass->getProperty($name);
			$property->setAccessible(true);

			if (count($arguments))
			{
				$property->setValue($entity, $arguments[0]);

				return $entity;
			}

			return $property->getValue($entity);
		}

		// invoke method
		if ($reflectionClass->hasMethod($name))
		{
			$method = $reflectionClass->getMethod($name);

			if ($method->isPublic())
			{
				return $method->invokeArgs($entity, $arguments);
			}
		}

		return null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setCurrentLocale(string $currentLocale): TranslateInterface
	{
		$this->currentLocale = $currentLocale;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getCurrentLocale(): string
	{
		return $this->currentLocale;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setDefaultLocale(string $defaultLocale): TranslateInterface
	{
		$this->defaultLocale = $defaultLocale;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDefaultLocale(): string
	{
		return $this->defaultLocale;
	}

	/**
	 * {@inheritdoc}
	 */
	public function addTranslation(TranslationInterface $translation): TranslateInterface
	{
		$translation->setTranslatable($this);

		$this->getTranslations()->set($translation->getLocale(), $translation);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function removeTranslation(TranslationInterface $translation): TranslateInterface
	{
		$this->getTranslations()->removeElement($translation);

		$translation->setTranslatable(null);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTranslations(): Collection
	{
		return $this->translations = $this->translations ?: new ArrayCollection();
	}

	/**
	 * {@inheritdoc}
	 */
	public function addNewTranslation(TranslationInterface $translation): TranslateInterface
	{
		$translation->setTranslatable($this);

		$this->getNewTranslations()->set($translation->getLocale(), $translation);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function removeNewTranslation(TranslationInterface $translation): TranslateInterface
	{
		$this->getNewTranslations()->removeElement($translation);

		$translation->setTranslatable(null);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getNewTranslations(): Collection
	{
		return $this->newTranslations = $this->newTranslations ?: new ArrayCollection();
	}

	/**
	 * {@inheritdoc}
	 */
	public function mergeNewTranslations(): TranslateInterface
	{
		$translations = $this->getTranslations();

		$newTranslations = $this->getNewTranslations();

		foreach ($newTranslations as $translation)
		{
			if ($translations->contains($translation))
			{
				continue;
			}

			$this->addTranslation($translation);

			$newTranslations->removeElement($translation);
		}

		return $this;
	}

	/**
	 * Do translate
	 *
	 * @param string $locale [optional]
	 * @param bool $fallback [optional]
	 *
	 * @return TranslationInterface
	 */
	protected function doTranslate(string $locale = null, bool $fallback = true): TranslationInterface
	{
		if ($locale === null)
		{
			$locale = $this->currentLocale;
		}

		if (($translation = $this->findTranslationByLocale($locale)) !== null)
		{
			return $translation;
		}

		if ($fallback)
		{
			if (($fallbackLocale = $this->computeFallbackLocale($locale)) &&
				($translation = $this->findTranslationByLocale($fallbackLocale)) !== null)
			{
				return $translation;
			}

			if (($translation = $this->findTranslationByLocale($this->defaultLocale, false)) !== null)
			{
				return $translation;
			}
		}

		$className = get_class($this) . 'Translation';

		/**
		 * @var TranslationInterface $translation
		 */
		$translation = new $className();
		$translation->setLocale($locale);

		$this->addNewTranslation($translation);

		return $translation;
	}

	/**
	 * Find translation by locale
	 *
	 * @param string $locale
	 * @param bool $withNewTranslations [optional]
	 *
	 * @return TranslationInterface
	 */
	private function findTranslationByLocale(string $locale, bool $withNewTranslations = true): ?TranslationInterface
	{
		if (($translations = $this->getTranslations()) && $translations->containsKey($locale))
		{
			return $translations->get($locale);
		}

		if ($withNewTranslations)
		{
			if (($newTranslations = $this->getNewTranslations()) && $newTranslations->containsKey($locale))
			{
				return $newTranslations->get($locale);
			}
		}

		return null;
	}

	/**
	 * Compute fallback locale
	 *
	 * @param string $locale
	 *
	 * @return string
	 */
	private function computeFallbackLocale(string $locale): ?string
	{
		if (strpos($locale, '_') === 2)
		{
			return substr($locale, 0, 2);
		}

		return null;
	}

	/**
	 * On clone translations
	 */
	protected function onCloneTranslations(): void
	{
		$translations = $this->getTranslations();
		
		$this->translations = new ArrayCollection();

		foreach ($translations as $translation)
		{
			$this->addTranslation(clone $translation);
		}
	}
}