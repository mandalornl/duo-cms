<?php

namespace Duo\TranslatorBundle\Controller\Listing;

use Duo\TranslatorBundle\Entity\Entry;
use Duo\TranslatorBundle\Form\Listing\EntryType;

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
		return EntryType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getType(): string
	{
		return 'entry';
	}
}