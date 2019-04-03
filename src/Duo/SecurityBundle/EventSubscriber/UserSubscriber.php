<?php

namespace Duo\SecurityBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Duo\SecurityBundle\Entity\UserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserSubscriber implements EventSubscriber
{
	/**
	 * @var UserPasswordEncoderInterface
	 */
	private $encoder;

	/**
	 * @var ValidatorInterface
	 */
	private $validator;

	/**
	 * UserSubscriber constructor
	 *
	 * @param UserPasswordEncoderInterface $encoder
	 * @param ValidatorInterface $validator
	 */
	public function __construct(UserPasswordEncoderInterface $encoder, ValidatorInterface $validator)
	{
		$this->encoder = $encoder;
		$this->validator = $validator;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getSubscribedEvents(): array
	{
		return [
			Events::prePersist,
			Events::preUpdate
		];
	}

	/**
	 * On pre persist event
	 *
	 * @param LifecycleEventArgs $args
	 */
	public function prePersist(LifecycleEventArgs $args): void
	{
		$entity = $args->getObject();

		if (!$entity instanceof UserInterface)
		{
			return;
		}

		$this->setUsername($entity);
		$this->setPassword($entity);
		$this->setEmail($entity);
	}

	/**
	 * On pre update event
	 *
	 * @param PreUpdateEventArgs $args
	 */
	public function preUpdate(PreUpdateEventArgs $args): void
	{
		$entity = $args->getObject();

		if (!$entity instanceof UserInterface)
		{
			return;
		}

		$this->setUsername($entity);
		$this->setPassword($entity);
		$this->setEmail($entity);
	}

	/**
	 * Set password
	 *
	 * @param UserInterface $entity
	 */
	private function setPassword(UserInterface $entity): void
	{
		if ($entity->getPlainPassword() === null)
		{
			return;
		}

		$entity->setPassword($this->encoder->encodePassword($entity, $entity->getPlainPassword()));
	}

	/**
	 * Set username
	 *
	 * @param UserInterface $entity
	 */
	private function setUsername(UserInterface $entity): void
	{
		$errors = $this->validator->validate(
			$entity->getUsername(),
			new Assert\Email()
		);

		if (!count($errors))
		{
			$entity->setUsername(mb_strtolower($entity->getUsername(), 'UTF-8'));
		}
	}

	/**
	 * Set email
	 *
	 * @param UserInterface $entity
	 */
	private function setEmail(UserInterface $entity): void
	{
		if ($entity->getEmail() === null)
		{
			return;
		}

		$entity->setEmail(mb_strtolower($entity->getEmail(), 'UTF-8'));
	}
}
