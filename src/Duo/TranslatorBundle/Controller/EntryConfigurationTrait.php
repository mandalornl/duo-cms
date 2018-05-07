<?php

namespace Duo\TranslatorBundle\Controller;

use Duo\TranslatorBundle\Entity\Entry;
use Duo\TranslatorBundle\Form\EntryListingType;

trait EntryConfigurationTrait
{
	/**
	 * {@inheritdoc}
	 */
	protected function getEntityClass(): string
	{
		return Entry::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getFormType(): ?string
	{
		return EntryListingType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getType(): string
	{
		return 'entry';
	}
}