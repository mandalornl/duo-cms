<?php

namespace Softmedia\AdminBundle\Controller\Behavior;

use Doctrine\Common\Persistence\ObjectManager;
use Softmedia\AdminBundle\Controller\AbstractAdminController;
use Softmedia\AdminBundle\Entity\Behavior\SortableInterface;
use Softmedia\AdminBundle\Entity\Behavior\TreeableInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait SortableTrait
{
	/**
	 * Move entity up
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse
	 */
	protected function doMoveUpAction(Request $request, int $id)
	{
		/**
		 * @var AbstractAdminController $this
		 */
		$repository = $this->getDoctrine()->getRepository($this->getEntityClassName());

		if (($entity = $repository->find($id)) === null)
		{
			return $this->entityNotFound($id);
		}

		if (!$entity instanceof SortableInterface)
		{
			return $this->sortableInterfaceNotImplemented($id);
		}

		/**
		 * @var SortableInterface $previousEntity
		 */
		$previousEntity = $repository->findPreviousWeight($entity);
		if ($previousEntity !== null)
		{
			$weight = $previousEntity->getWeight();
			$previousEntity->setWeight($entity->getWeight());
			$entity->setWeight($weight);

			/**
			 * @var ObjectManager $em
			 */
			$em = $this->getDoctrine()->getManager();
			$em->persist($previousEntity);
			$em->persist($entity);
			$em->flush();
		}

		return $this->redirectToRoute("softmedia_admin_{$this->getListType()}_list");
	}

	/**
	 * Move entity down
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse
	 */
	protected function doMoveDownAction(Request $request, int $id)
	{
		/**
		 * @var AbstractAdminController $this
		 */
		$repository = $this->getDoctrine()->getRepository($this->getEntityClassName());

		if (($entity = $repository->find($id)) === null)
		{
			return $this->entityNotFound($id);
		}

		if (!$entity instanceof SortableInterface)
		{
			return $this->sortableInterfaceNotImplemented($id);
		}

		/**
		 * @var SortableInterface $nextEntity
		 */
		$nextEntity = $repository->findNextWeight($entity);
		if ($nextEntity !== null)
		{
			$weight = $nextEntity->getWeight();
			$nextEntity->setWeight($entity->getWeight());
			$entity->setWeight($weight);

			/**
			 * @var ObjectManager $em
			 */
			$em = $this->getDoctrine()->getManager();
			$em->persist($nextEntity);
			$em->persist($entity);
			$em->flush();
		}

		return $this->redirectToRoute("softmedia_admin_{$this->getListType()}_list");
	}

	/**
	 * Move entity to
	 *
	 * @param Request $request
	 * @param int $id
	 * @param int $weight
	 * @param int $parentId [optional]
	 *
	 * @return Response|RedirectResponse
	 */
	protected function doMoveToAction(Request $request, int $id, int $weight, int $parentId = null)
	{
		/**
		 * @var AbstractAdminController $this
		 */
		$repository = $this->getDoctrine()->getRepository($this->getEntityClassName());

		if ((!$entity = $repository->find($id)) === null)
		{
			return $this->entityNotFound($id);
		}

		if (!$entity instanceof SortableInterface)
		{
			return $this->sortableInterfaceNotImplemented($id);
		}

		$criteria = [
			'weight' => $weight
		];

		// use parent of entity
		if ($entity instanceof TreeableInterface)
		{
			$criteria['parent'] = $entity->getParent();
		}

		/**
		 * @var SortableInterface $currentEntity
		 */
		$currentEntity = $repository->findOneBy($criteria);

		/**
		 * @var ObjectManager $em
		 */
		$em = $this->getDoctrine()->getManager();

		if ($currentEntity !== null)
		{
			$entity->setWeight($currentEntity->getWeight());
			$em->persist($entity);

			$weight = $currentEntity->getWeight();
			$currentEntity->setWeight($weight++);

			$em->persist($currentEntity);

			/**
			 * @var SortableInterface[] $entities
			 */
			$entities = $repository->findNextWeight($currentEntity, null);
			foreach ($entities as $entity)
			{
				$entity->setWeight($weight++);
				$em->persist($entity);
			}
		}
		else
		{
			$entity->setWeight($weight);
			$em->persist($entity);
		}

		$em->flush();

		return $this->redirectToRoute("softmedia_admin_{$this->getListType()}_list");
	}

	/**
	 * Sortable interface not implemented
	 *
	 * @param int $id
	 *
	 * @return Response
	 */
	private function sortableInterfaceNotImplemented(int $id): Response
	{
		$interface = SortableInterface::class;
		return new Response("Entity of type '{$this->getEntityClassName()}' with id '{$id}' doesn't implement '{$interface}'", 500);
	}

	/**
	 * Move entity up
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse
	 */
	abstract public function moveUpAction(Request $request, int $id);

	/**
	 * Move entity down
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse
	 */
	abstract public function moveDownAction(Request $request, int $id);

	/**
	 * Move entity to
	 *
	 * @param Request $request
	 * @param int $id
	 * @param int $weight
	 *
	 * @return Response|RedirectResponse
	 */
	abstract public function moveToAction(Request $request, int $id, int $weight);
}