<?php

namespace Duo\DraftBundle\Twig;

use Duo\DraftBundle\Entity\Property\DraftInterface;

class DraftTwigExtension extends \Twig_Extension
{
	/**
	 * {@inheritdoc}
	 */
	public function getTests(): array
	{
		return [
			new \Twig_SimpleTest('draftable', [$this, 'isDraftable'])
		];
	}

	/**
	 * Is draftable
	 *
	 * @param object $entity
	 *
	 * @return bool
	 */
	public function isDraftable(object $entity): bool
	{
		return $entity instanceof DraftInterface;
	}
}