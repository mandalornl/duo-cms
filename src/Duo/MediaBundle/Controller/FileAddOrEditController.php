<?php

namespace Duo\MediaBundle\Controller;

use Duo\AdminBundle\Controller\AbstractController;
use Duo\MediaBundle\Entity\File;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/media/file", name="duo_media_listing_file_")
 */
class FileAddOrEditController extends AbstractController
{
	use FileConfigurationTrait;

	/**
	 * Add or edit entity
	 *
	 * @Route("/add", name="add", defaults={ "id" = null })
	 * @Route("/{id}", name="edit", requirements={ "id" = "\d+" })
	 * @Method({"GET", "POST"})
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|JsonResponse|RedirectResponse
	 *
	 * @throws \Throwable
	 */
	public function addOrEditAction(Request $request, int $id = null)
	{
		if ($id !== null)
		{
			$entity = $this->getDoctrine()->getRepository(File::class)->find($id);

			if ($entity === null)
			{
				return $this->entityNotFound($request, $id);
			}
		}
		else
		{
			$entity = new File();
		}

		$form = $this->createForm($this->getFormType(), $entity);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			/**
			 * @var UploadedFile $file
			 */
			$file = $form->get('file');

			$uuid = md5(uniqid());

			$metadata = [
				'basename' => $file->getBasename(),
				'extension' => $file->getExtension(),
				'filename' => $file->getFilename()
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
				->setUrl("{$this->getParameter('duo.media.relative_upload_path')}/{$uuid}/{$file->getBasename()}");

			if ($entity->getName() === null)
			{
				$entity->setName($file->getBasename());
			}

			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			$file->move("{$this->getParameter('duo.media.absolute_upload_path')}/{$uuid}", $file->getBasename());

			$folderId = null;
			if (($folder = $entity->getFolder()) !== null)
			{
				$folderId = $folder->getId();
			}

			return $this->redirectToRoute('duo_media_listing_folder_index', [
				'id' => $folderId
			]);
		}

		return $this->render('@DuoMedia/Listing/file.html.twig', (array)$this->getDefaultContext([
			'form' => $form->createView(),
			'entity' => $entity
		]));
	}
}