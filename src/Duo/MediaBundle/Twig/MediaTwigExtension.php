<?php

namespace Duo\MediaBundle\Twig;

use Duo\MediaBundle\Entity\ImageCrop;

class MediaTwigExtension extends \Twig_Extension
{
	/**
	 * {@inheritDoc}
	 */
	public function getFilters(): array
	{
		return [
			new \Twig_SimpleFilter('humanizedbytes', 'Duo\MediaBundle\Tools\Unit\Byte::humanize')
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public function getFunctions(): array
	{
		return [
			new \Twig_SimpleFunction('get_crop', [$this, 'getCrop']),
			new \Twig_SimpleFunction('get_crop_imagine_config', [$this, 'getCropImagineConfig'])
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTests(): array
	{
		return [
			new \Twig_SimpleTest('croppable', [$this, 'isCroppable'])
		];
	}

	/**
	 * Get crop
	 *
	 * @param ImageCrop $entity
	 *
	 * @return array
	 */
	public function getCrop(ImageCrop $entity): ?array
	{
		if (empty($entity->getCrop()))
		{
			return null;
		}

		list($x, $y, $width, $height) = explode(';', $entity->getCrop());

		return [
			'x' => (float)$x,
			'y' => (float)$y,
			'width' => (float)$width,
			'height' => (float)$height
		];
	}

	/**
	 * Get crop imagine config
	 *
	 * @param ImageCrop $entity
	 *
	 * @return array
	 */
	public function getCropImagineConfig(ImageCrop $entity): array
	{
		if (empty($entity->getCrop()) || ($media = $entity->getMedia()) === null)
		{
			return [];
		}

		list($x, $y, $width, $height) = explode(';', $entity->getCrop());

		$startX = $media->getOriginalWidth() * (float)$x;
		$startY = $media->getOriginalHeight() * (float)$y;

		return [
			'crop' => [
				'start' => [
					$startX,
					$startY
				],
				'size' => [
					($media->getOriginalWidth() * (float)$width) - $startX,
					($media->getOriginalHeight() * (float)$height) - $startY
				]
			]
		];
	}

	/**
	 * Is croppable
	 *
	 * @param mixed $resource
	 *
	 * @return bool
	 */
	public function isCroppable($resource = null): bool
	{
		return is_object($resource) &&
			$resource instanceof ImageCrop &&
			$resource->getMedia() !== null &&
			!empty($resource->getCrop());
	}
}
