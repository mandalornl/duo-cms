<?php

namespace Duo\AdminBundle\Controller\Listing;

use Duo\CoreBundle\Entity\Property\PublishInterface;
use Duo\CoreBundle\Entity\Property\TranslateInterface;
use Duo\CoreBundle\Event\PublishEvent;
use Duo\CoreBundle\Event\PublishEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractPublishController extends AbstractController
{
	/**
	 * Publish action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws \Throwable
	 */
	protected function doPublishAction(Request $request, int $id): Response
	{
		return $this->handlePublicationRequest(function(PublishInterface $entity, EventDispatcherInterface $dispatcher)
		{
			$entity->publish();

			$dispatcher->dispatch(PublishEvents::PUBLISH, new PublishEvent($entity));
		}, 'duo.admin.listing.alert.publish_success', $request, $id);
	}

	/**
	 * Publish action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws \Throwable
	 */
	abstract public function publishAction(Request $request, int $id): Response;

	/**
	 * Unpublish action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws \Throwable
	 */
	protected function doUnpublishAction(Request $request, int $id): Response
	{
		return $this->handlePublicationRequest(function(PublishInterface $entity, EventDispatcherInterface $dispatcher)
		{
			$entity->unpublish();

			$dispatcher->dispatch(PublishEvents::UNPUBLISH, new PublishEvent($entity));
		}, 'duo.admin.listing.alert.unpublish_success', $request, $id);
	}

	/**
	 * Unpublish action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws \Throwable
	 */
	abstract public function unpublishAction(Request $request, int $id): Response;

	/**
	 * Handle publication request
	 *
	 * @param \Closure $callback
	 * @param string $message
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws \Throwable
	 */
	private function handlePublicationRequest(\Closure $callback, string $message, Request $request, int $id): Response
	{
		$entity = $this->getDoctrine()->getRepository($this->getEntityClass())->find($id);

		if ($entity === null)
		{
			return $this->entityNotFound($request, $id);
		}

		$dispatcher = $this->get('event_dispatcher');

		if ($entity instanceof PublishInterface)
		{
			call_user_func_array($callback, [ $entity, $dispatcher ]);
		}
		else
		{
			if ($entity instanceof TranslateInterface)
			{
				$translation = $entity->getTranslations()->first();

				if ($translation instanceof PublishInterface)
				{
					foreach ($entity->getTranslations() as $translation)
					{
						call_user_func_array($callback, [ $translation, $dispatcher ]);
					}
				}
				else
				{
					return $this->interfaceNotImplemented($request, $id, PublishInterface::class);
				}
			}
			else
			{
				return $this->interfaceNotImplemented($request, $id, PublishInterface::class);
			}
		}

		$manager = $this->getDoctrine()->getManager();
		$manager->persist($entity);
		$manager->flush();

		// reply with json response
		if ($request->getRequestFormat() === 'json')
		{
			return $this->json([
				'success' => true,
				'id' => $entity->getId(),
				'message' => $this->get('translator')->trans($message)
			]);
		}

		$this->addFlash('success', $this->get('translator')->trans($message));

		return $this->redirectToRoute("{$this->getRoutePrefix()}_update", [
			'id' => $entity->getId()
		]);
	}
}