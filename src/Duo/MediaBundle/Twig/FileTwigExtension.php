<?php

namespace Duo\Media\Twig;

class FileTwigExtension extends \Twig_Extension
{
	/**
	 * {@inheritdoc}
	 */
	public function getFilters(): array
	{
		return [
			new \Twig_SimpleFilter('humanizedbytes', 'Duo\Media\Helper\FileHelper::humanizeBytes')
		];
	}
}