<?php

namespace Duo\MediaBundle\Helper;

use Duo\AdminBundle\Tools\Intl\Slugifier;
use Duo\MediaBundle\Entity\Media;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadHelper
{
	/**
	 * @var string
	 */
	private $relativePath;

	/**
	 * @var string
	 */
	private $absolutePath;

	/**
	 * UploadHelper constructor
	 *
	 * @param string $relativePath
	 * @param string $absolutePath
	 */
	public function __construct(string $relativePath, string $absolutePath)
	{
		$this->relativePath = $relativePath;
		$this->absolutePath = $absolutePath;
	}

	/**
	 * Upload
	 *
	 * @param UploadedFile $file
	 * @param Media $entity
	 *
	 * @throws \IntlException
	 */
	public function upload(UploadedFile $file, Media $entity): void
	{
		$extension = $file->getExtension() ?: $file->guessExtension();
		$filename = basename($file->getClientOriginalName(), ".{$extension}");

		$metadata = [
			'basename' => $file->getClientOriginalName(),
			'extension' => $extension,
			'filename' => $filename
		];

		// get image width/height
		if (strpos($file->getMimeType(), 'image/') === 0)
		{
			list($width, $height) = @getimagesize($file);

			$metadata = array_merge($metadata, [
				'width' => $width,
				'height' => $height
			]);
		}

		// slugify filename
		$filename = Slugifier::slugify($filename);

		$entity
			->setSize($file->getSize())
			->setMimeType($file->getMimeType())
			->setMetadata($metadata)
			->setUrl("{$this->relativePath}/{$entity->getUuid()}/{$filename}.{$extension}");

		if ($entity->getName() === null)
		{
			$entity->setName($file->getClientOriginalName());
		}

		$file->move("{$this->absolutePath}/{$entity->getUuid()}", "{$filename}.{$extension}");
	}
}
