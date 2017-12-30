<?php

namespace Duo\FormBundle\Form;

use Doctrine\Common\Collections\ArrayCollection;

interface FormInterface
{
	/**
	 * Get message
	 *
	 * @param string $message
	 *
	 * @return FormInterface
	 */
	public function setMessage(string $message = null): FormInterface;

	/**
	 * Get message
	 *
	 * @return string
	 */
	public function getMessage(): ?string;

	/**
	 * Set subject
	 *
	 * @param string $subject
	 *
	 * @return FormInterface
	 */
	public function setSubject(string $subject = null): FormInterface;

	/**
	 * Get subject
	 *
	 * @return string
	 */
	public function getSubject(): ?string;

	/**
	 * Set emailFrom
	 *
	 * @param string $emailFrom
	 *
	 * @return FormInterface
	 */
	public function setEmailFrom(string $emailFrom = null): FormInterface;

	/**
	 * Get emailFrom
	 *
	 * @return string
	 */
	public function getEmailFrom(): ?string;

	/**
	 * Set emailTo
	 *
	 * @param string $emailTo
	 *
	 * @return FormInterface
	 */
	public function setEmailTo(string $emailTo = null): FormInterface;

	/**
	 * Get emailTo
	 *
	 * @return string
	 */
	public function getEmailTo(): ?string;

	/**
	 * Add formType
	 *
	 * @param FormTypeInterface $formType
	 *
	 * @return FormInterface
	 */
	public function addFormType(FormTypeInterface $formType): FormInterface;

	/**
	 * Remove formType
	 *
	 * @param FormTypeInterface $formType
	 *
	 * @return mixed
	 */
	public function removeFormType(FormTypeInterface $formType): FormInterface;

	/**
	 * Get formTypes
	 *
	 * @return ArrayCollection
	 */
	public function getFormTypes();
}