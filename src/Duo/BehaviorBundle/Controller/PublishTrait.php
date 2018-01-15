<?php

namespace Duo\BehaviorBundle\Controller;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Persistence\ObjectManager;
use Duo\AdminBundle\Controller\Listing\AbstractController;
use Duo\BehaviorBundle\Entity\PublishInterface;
use Duo\BehaviorBundle\Entity\TranslateInterface;
use Duo\BehaviorBundle\Event\PublishEvent;
use Duo\BehaviorBundle\Event\PublishEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

trait PublishTrait
{
	/**
	 * Publish action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws AnnotationException
	 */
	protected function doPublishAction(Request $request, int $id)
	{
		return $this->handlePublicationRequest($request, $id, function(PublishInterface $entity)
		{
			$entity->publish();
		}, PublishEvents::PUBLISH);
	}

	/**
	 * Unpublish action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws AnnotationException
	 */
	protected function doUnpublishAction(Request $request, int $id)
	{
		return $this->handlePublicationRequest($request, $id, function(PublishInterface $entity)
		{
			$entity->unpublish();
		}, PublishEvents::UNPUBLISH);
	}

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
	 * @throws AnnotationException
	 */
	private function handlePublicationRequest(Request $request, int $id, \Closure $callback, string $eventName)
	{
		/**
		 * @var AbstractController $this
		 */
		$entity = $this->getDoctrine()->getRepository($this->getEntityClassName())->find($id);
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
			return new JsonResponse([
				'result' => [
					'success' => true,
					'id' => $entity->getId()
				]
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
		$error = "Entity '{$this->getEntityClassName()}::{$id}' doesn't implement '{$interface}'";

		/// reply with json response
		if ($request->getRequestFormat() === 'json')
		{
			return new JsonResponse([
				'result' => [
					'success' =>  false,
					'error' => $error
				]
			]);
		}

		throw $this->createNotFoundException($error);
	}
}