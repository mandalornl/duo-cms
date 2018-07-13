<?php

namespace Duo\MediaBundle\Helper;

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
	 */
	public function upload(UploadedFile $file, Media $entity): void
	{
		$uuid = self::getUuid();
		$extension = $file->guessExtension();

		$metadata = [
			'basename' => $file->getClientOriginalName(),
			'extension' => $extension,
			'filename' => basename($file->getClientOriginalName(), ".{$extension}")
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

		$entity
			->setUuid($uuid)
			->setSize($file->getSize())
			->setMimeType($file->getMimeType())
			->setMetadata($metadata)
			->setUrl("{$this->relativeUploadPath}/{$uuid}/{$file->getClientOriginalName()}");

		if ($entity->getName() === null)
		{
			$entity->setName($file->getClientOriginalName());
		}

		$file->move("{$this->absoluteUploadPath}/{$uuid}", $file->getClientOriginalName());
	}

	/**
	 * Get uuid
	 *
	 * @param int $length [optional]
	 *
	 * @return string
	 */
	public static function getUuid(int $length = 16): string
	{
		return bin2hex(random_bytes($length));
	}
}