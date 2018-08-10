<?php

namespace Duo\MediaBundle\Controller;

use Duo\AdminBundle\Controller\AbstractController;
use Duo\MediaBundle\Entity\Media;
use Duo\MediaBundle\Helper\UploadHelper;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/media", name="duo_media_listing_media_")
 */
class MediaUploadController extends AbstractController
{
	use MediaConfigurationTrait;

	/**
	 * Upload action
	 *
	 * @Route("/upload", name="upload", methods={ "PUT" })
	 *
	 * @param Request $request
	 * @param UploadHelper $uploadHelper
	 *
	 * @return JsonResponse
	 */
	public function uploadAction(Request $request, UploadHelper $uploadHelper): JsonResponse
	{
		$tmpDir = sys_get_temp_dir();

		if (($tmpFile = tempnam($tmpDir, 'php')) === false)
		{
			return $this->json([
				'error' => "Temp file can not be created in '{$tmpDir}'"
			]);
		}

		try
		{
			if (file_put_contents($tmpFile, $request->getContent()) === false)
			{
				return $this->json([
					'error' => 'Unable to write temporary file'
				]);
			}

			$file = new UploadedFile(
				$tmpFile,
				$request->query->get('filename'),
				$request->query->get('mimeType'),
				$request->query->get('size'),
				null,
				true // ensure local file handling
			);

			$entity = new Media();

			$uploadHelper->upload($file, $entity);

			$manager = $this->getDoctrine()->getManager();
			$manager->persist($entity);
			$manager->flush();

			return $this->json([
				'result' => [
					'id' => $entity->getId(),
					'name' => $entity->getName(),
					'url' => $entity->getUrl()
				]
			]);
		}
		catch (\Exception $e)
		{
			unlink($tmpFile);

			return $this->json([
				'error' => (string)$e->getMessage()
			]);
		}
	}
}