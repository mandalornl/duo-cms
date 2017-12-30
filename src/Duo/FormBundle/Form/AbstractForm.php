<?php

namespace Duo\FormBundle\Form;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

abstract class AbstractForm implements FormInterface
{
	/**
	 * @var string
	 *
	 * @ORM\Column(name="message", type="string", nullable=true)
	 */
	protected $message;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="subject", type="string", nullable=true)
	 */
	protected $subject;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="email_from", type="string", nullable=true)
	 */
	protected $emailFrom;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="email_to", type="string", nullable=true)
	 */
	protected $emailTo;

	/**
	 * @var Collection
	 *
	 * // TODO: mapping using subscriber
	 */
	protected $formTypes;

	/**
	 * AbstractForm constructor
	 */
	public function __construct()
	{
		$this->formTypes = new ArrayCollection();
	}

	/**
	 * {@inheritdoc}
	 */
	public function setMessage(string $message = null): FormInterface
	{
		$this->message = $message;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getMessage(): ?string
	{
		return $this->message;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setSubject(string $subject = null): FormInterface
	{
		$this->subject = $subject;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSubject(): ?string
	{
		return $this->subject;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setEmailFrom(string $emailFrom = null): FormInterface
	{
		$this->emailFrom = $emailFrom;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEmailFrom(): ?string
	{
		return $this->emailFrom;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setEmailTo(string $emailTo = null): FormInterface
	{
		$this->emailTo = $emailTo;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEmailTo(): ?string
	{
		return $this->emailTo;
	}

	/**
	 * {@inheritdoc}
	 */
	public function addFormType(FormTypeInterface $formType): FormInterface
	{
		$this->formTypes[] = $formType;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function removeFormType(FormTypeInterface $formType): FormInterface
	{
		$this->formTypes->removeElement($formType);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFormTypes()
	{
		return $this->formTypes;
	}
}