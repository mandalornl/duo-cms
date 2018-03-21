<?php

namespace Duo\MediaBundle\Twig;

class FileTwigExtension extends \Twig_Extension
{
	/**
	 * {@inheritdoc}
	 */
	public function getFilters(): array
	{
		return [
			new \Twig_SimpleFilter('humanizedbytes', 'Duo\MediaBundle\Helper\FileHelper::humanizeBytes')
		];
	}
}