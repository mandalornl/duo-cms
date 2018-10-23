<?php

namespace Duo\CoreBundle\Controller\Listing;

use Duo\AdminBundle\Controller\Listing\AbstractController;
use Duo\CoreBundle\Entity\Property\PublishInterface;
use Duo\CoreBundle\Entity\Property\TranslateInterface;
use Duo\CoreBundle\Entity\Property\TreeInterface;
use Duo\CoreBundle\Event\Listing\PublishEvent;
use Duo\CoreBundle\Event\Listing\PublishEvents;
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
		$entity = $this->getDoctrine()->getRepository($this->getEntityClass())->find($id);

		if ($entity === null)
		{
			return $this->entityNotFound($request, $id);
		}

		if (!$this->doPublish($entity))
		{
			return $this->interfaceNotImplemented($request, $id, PublishInterface::class);
		}

		$manager = $this->getDoctrine()->getManager();
		$manager->flush();

		// reply with json response
		if ($request->getRequestFormat() === 'json')
		{
			return $this->json([
				'success' => true,
				'id' => $entity->getId(),
				'message' => $this->get('translator')->trans('duo.core.publish_success', [], 'flashes')
			]);
		}

		$this->addFlash('success', $this->get('translator')->trans('duo.core.publish_success', [], 'flashes'));

		return $this->redirectToRoute("{$this->getRoutePrefix()}_update", [
			'id' => $entity->getId()
		]);
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
		$entity = $this->getDoctrine()->getRepository($this->getEntityClass())->find($id);

		if ($entity === null)
		{
			return $this->entityNotFound($request, $id);
		}

		if (!$this->doUnpublish($entity))
		{
			return $this->interfaceNotImplemented($request, $id, PublishInterface::class);
		}

		$manager = $this->getDoctrine()->getManager();
		$manager->flush();

		// reply with json response
		if ($request->getRequestFormat() === 'json')
		{
			return $this->json([
				'success' => true,
				'id' => $entity->getId(),
				'message' => $this->get('translator')->trans('duo.core.unpublish_success', [], 'flashes')
			]);
		}

		$this->addFlash('success', $this->get('translator')->trans('duo.core.unpublish_success', [], 'flashes'));

		return $this->redirectToRoute("{$this->getRoutePrefix()}_update", [
			'id' => $entity->getId()
		]);
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
	 * Do publish
	 *
	 * @param object $entity
	 *
	 * @return bool
	 */
	protected function doPublish(object $entity): bool
	{
		$manager = $this->getDoctrine()->getManager();

		if ($entity instanceof PublishInterface)
		{
			if (!$entity->isPublished())
			{
				$entity->publish();

				// dispatch publish event
				$this->get('event_dispatcher')->dispatch(PublishEvents::PUBLISH, new PublishEvent($entity));

				$manager->persist($entity);
			}
		}
		else
		{
			if (!$entity instanceof TranslateInterface)
			{
				return false;
			}

			foreach ($entity->getTranslations() as $translation)
			{
				if (!$translation instanceof PublishInterface)
				{
					return false;
				}

				if (!$translation->isPublished())
				{
					$translation->publish();

					// dispatch publish event
					$this->get('event_dispatcher')->dispatch(PublishEvents::PUBLISH, new PublishEvent($translation));

					$manager->persist($translation);
				}
			}
		}

		if ($entity instanceof TreeInterface)
		{
			if (($parent = $entity->getParent()) !== null)
			{
				$this->doPublish($parent);
			}
		}

		return true;
	}

	/**
	 * Do unpublish
	 *
	 * @param object $entity
	 *
	 * @return bool
	 */
	protected function doUnpublish(object $entity): bool
	{
		$manager = $this->getDoctrine()->getManager();

		if ($entity instanceof PublishInterface)
		{
			if ($entity->isPublished())
			{
				$entity->unpublish();

				// dispatch unpublish event
				$this->get('event_dispatcher')->dispatch(PublishEvents::UNPUBLISH, new PublishEvent($entity));

				$manager->persist($entity);
			}
		}
		else
		{
			if (!$entity instanceof TranslateInterface)
			{
				return false;
			}

			foreach ($entity->getTranslations() as $translation)
			{
				if (!$translation instanceof PublishInterface)
				{
					return false;
				}

				if ($translation->isPublished())
				{
					$translation->unpublish();

					// dispatch unpublish event
					$this->get('event_dispatcher')->dispatch(PublishEvents::UNPUBLISH, new PublishEvent($translation));

					$manager->persist($translation);
				}
			}
		}

		// unpublish children too
		if ($entity instanceof TreeInterface)
		{
			foreach ($entity->getChildren() as $child)
			{
				$this->doUnpublish($child);
			}
		}

		return true;
	}
}