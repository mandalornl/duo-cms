<?php

namespace Duo\MediaBundle\Twig;

class MediaTwigExtension extends \Twig_Extension
{
	/**
	 * {@inheritdoc}
	 */
	public function getFilters(): array
	{
		return [
			new \Twig_SimpleFilter('humanizedbytes', 'Duo\MediaBundle\Tools\Unit\Byte::humanize')
		];
	}
}