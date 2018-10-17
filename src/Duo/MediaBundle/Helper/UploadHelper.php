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
	private $relativeUploadPath;

	/**
	 * @var string
	 */
	private $absoluteUploadPath;

	/**
	 * UploadHelper constructor
	 *
	 * @param string $relativeUploadPath
	 * @param string $absoluteUploadPath
	 */
	public function __construct(string $relativeUploadPath, string $absoluteUploadPath)
	{
		$this->relativeUploadPath = $relativeUploadPath;
		$this->absoluteUploadPath = $absoluteUploadPath;
	}

	/**
	 * Upload
	 *
	 * @param UploadedFile $file
	 * @param Media $entity
	 *
	 * @throws \Throwable
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
			->setUrl("{$this->relativeUploadPath}/{$entity->getUuid()}/{$filename}.{$extension}");

		if ($entity->getName() === null)
		{
			$entity->setName($file->getClientOriginalName());
		}

		$file->move("{$this->absoluteUploadPath}/{$entity->getUuid()}", "{$filename}.{$extension}");
	}
}