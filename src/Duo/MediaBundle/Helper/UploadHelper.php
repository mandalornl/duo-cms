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
		$uuid = self::getUuid();

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
			->setUuid($uuid)
			->setSize($file->getSize())
			->setMimeType($file->getMimeType())
			->setMetadata($metadata)
			->setUrl("{$this->relativeUploadPath}/{$uuid}/{$filename}.{$extension}");

		if ($entity->getName() === null)
		{
			$entity->setName($file->getClientOriginalName());
		}

		$file->move("{$this->absoluteUploadPath}/{$uuid}", "{$filename}.{$extension}");
	}

	/**
	 * Get uuid
	 *
	 * @param int $length [optional]
	 *
	 * @return string
	 *
	 * @throws \Throwable
	 */
	public static function getUuid(int $length = 16): string
	{
		return bin2hex(random_bytes($length));
	}
}