<?php

namespace Duo\MediaBundle\Controller;

use Duo\MediaBundle\Entity\Media;
use Duo\MediaBundle\Helper\UploadHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UploadController extends AbstractController
{
	/**
	 * @var UploadHelper
	 */
	private $uploadHelper;

	/**
	 * UploadController constructor
	 *
	 * @param UploadHelper $uploadHelper
	 */
	public function __construct(UploadHelper $uploadHelper)
	{
		$this->uploadHelper = $uploadHelper;
	}

	/**
	 * Upload action
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function uploadAction(Request $request): JsonResponse
	{
		$tmpDir = sys_get_temp_dir();

		if (($tmpFile = tempnam($tmpDir, 'php')) === false)
		{
			return $this->json([
				'error' => "Temp file can not be created in '{$tmpDir}'",
				'message' => $this->get('translator')->trans('duo_admin.error', [], 'flashes')
			], Response::HTTP_BAD_REQUEST);
		}

		try
		{
			if (file_put_contents($tmpFile, $request->getContent()) === false)
			{
				return $this->json([
					'error' => 'Unable to write temporary file',
					'message' => $this->get('translator')->trans('duo_admin.error', [], 'flashes')
				], Response::HTTP_BAD_REQUEST);
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

			$this->uploadHelper->upload($file, $entity);

			$manager = $this->getDoctrine()->getManager();
			$manager->persist($entity);
			$manager->flush();

			return $this->json([
				'result' => [
					'id' => $entity->getId(),
					'name' => $entity->getName(),
					'mimeType' => $entity->getMimeType(),
					'url' => $entity->getUrl()
				]
			]);
		}
		catch (\Exception $e)
		{
			unlink($tmpFile);

			return $this->json([
				'error' => $e->getMessage(),
				'message' => $this->get('translator')->trans('duo_admin.error', [], 'flashes')
			], Response::HTTP_BAD_REQUEST);
		}
	}
}
