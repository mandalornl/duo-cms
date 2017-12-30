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
	 * {@inheritdoc}
	 */
	public function getSubscribedEvents()
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
	public function prePersist(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();

		if (!$entity instanceof UserInterface)
		{
			return;
		}

		$this->setUsername($entity);
		$this->setPassword($entity);
	}

	/**
	 * On pre update event
	 *
	 * @param PreUpdateEventArgs $args
	 */
	public function preUpdate(PreUpdateEventArgs $args)
	{
		$entity = $args->getObject();

		if (!$entity instanceof UserInterface)
		{
			return;
		}

		$this->setUsername($entity);
		$this->setPassword($entity);
	}

	/**
	 * Set password
	 *
	 * @param UserInterface $entity
	 */
	public function setPassword(UserInterface $entity)
	{
		if (($plainPassword = $entity->getPlainPassword()) !== null)
		{
			$password = $this->encoder->encodePassword($entity, $plainPassword);
			$entity->setPassword($password);
		}
	}

	/**
	 * Set username
	 *
	 * @param UserInterface $entity
	 */
	public function setUsername(UserInterface $entity)
	{
		$errors = $this->validator->validate(
			$entity->getUsername(),
			new Assert\Email()
		);

		if (!count($errors))
		{
			$username = mb_strtolower($entity->getUsername(), 'UTF-8');
			$entity->setUsername($username);
		}
	}
}