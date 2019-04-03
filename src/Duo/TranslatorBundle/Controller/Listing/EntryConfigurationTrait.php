<?php

namespace Duo\TranslatorBundle\Controller\Listing;

use Duo\TranslatorBundle\Entity\Entry;
use Duo\TranslatorBundle\Form\Listing\EntryType;

trait EntryConfigurationTrait
{
	/**
	 * {@inheritDoc}
	 */
	protected function getEntityClass(): string
	{
		return Entry::class;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getFormType(): ?string
	{
		return EntryType::class;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getType(): string
	{
		return 'entry';
	}
}
