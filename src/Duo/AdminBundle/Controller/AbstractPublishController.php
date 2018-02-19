<?php

namespace Duo\AdminBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Duo\BehaviorBundle\Entity\PublishInterface;
use Duo\BehaviorBundle\Entity\TranslateInterface;
use Duo\BehaviorBundle\Event\PublishEvent;
use Duo\BehaviorBundle\Event\PublishEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

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
	protected function doPublishAction(Request $request, int $id)
	{
		return $this->handlePublicationRequest($request, $id, function(PublishInterface $entity)
		{
			$entity->publish();
		}, PublishEvents::PUBLISH);
	}

	/**
	 * Publish action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse|JsonResponse
	 */
	abstract public function publishAction(Request $request, int $id);

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
	protected function doUnpublishAction(Request $request, int $id)
	{
		return $this->handlePublicationRequest($request, $id, function(PublishInterface $entity)
		{
			$entity->unpublish();
		}, PublishEvents::UNPUBLISH);
	}

	/**
	 * Unpublish action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse|JsonResponse
	 */
	abstract public function unpublishAction(Request $request, int $id);

	/**
	 * Handle publication request
	 *
	 * @param Request $request
	 * @param int $id
	 * @param \Closure $callback
	 * @param string $eventName
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws \Throwable
	 */
	private function handlePublicationRequest(Request $request, int $id, \Closure $callback, string $eventName)
	{
		$entity = $this->getDoctrine()->getRepository($this->getEntityClass())->find($id);

		if ($entity === null)
		{
			return $this->entityNotFound($request, $id);
		}

		/**
		 * @var EventDispatcherInterface $dispatcher
		 */
		$dispatcher = $this->get('event_dispatcher');

		if ($entity instanceof PublishInterface)
		{
			call_user_func($callback, $entity);

			$dispatcher->dispatch($eventName, new PublishEvent($entity));
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
						call_user_func($callback, $translation);

						$dispatcher->dispatch($eventName, new PublishEvent($translation));
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

		/**
		 * @var ObjectManager $em
		 */
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