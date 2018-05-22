<?php

namespace Duo\AdminBundle\Controller;

use Duo\CoreBundle\Entity\PublishInterface;
use Duo\CoreBundle\Entity\TranslateInterface;
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
		}, $request, $id);
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
		}, $request, $id);
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
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws \Throwable
	 */
	private function handlePublicationRequest(\Closure $callback, Request $request, int $id)
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
					return $this->publishInterfaceNotImplemented($request, $id);
				}
			}
			else
			{
				return $this->publishInterfaceNotImplemented($request, $id);
			}
		}

		$em = $this->getDoctrine()->getManager();
		$em->persist($entity);
		$em->flush();

		// reply with json response
		if ($request->getRequestFormat() === 'json')
		{
			return $this->json([
				'success' => true,
				'id' => $entity->getId()
			]);
		}

		return $this->redirectToRoute("{$this->getRoutePrefix()}_edit", [
			'id' => $entity->getId()
		]);
	}

	/**
	 * Publishable interface not implemented
	 *
	 * @param int $id
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	private function publishInterfaceNotImplemented(Request $request, int $id): JsonResponse
	{
		$interface = PublishInterface::class;

		$error = "Entity '{$this->getEntityClass()}::{$id}' doesn't implement '{$interface}'";

		// reply with json response
		if ($request->getRequestFormat() === 'json')
		{
			return $this->json([
				'error' => $error
			]);
		}

		throw $this->createNotFoundException($error);
	}
}