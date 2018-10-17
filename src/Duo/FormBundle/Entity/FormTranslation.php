<?php

namespace Duo\FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\CoreBundle\Entity\CloneTrait;
use Duo\CoreBundle\Entity\Property\TranslationInterface;
use Duo\CoreBundle\Entity\Property\TranslationTrait;
use Duo\PartBundle\Entity\Property\PartInterface;
use Duo\PartBundle\Entity\Property\PartTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="duo_form_translation")
 * @ORM\Entity()
 */
class FormTranslation implements TranslationInterface, PartInterface
{
	use CloneTrait;
	use PartTrait;
	use TranslationTrait;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="subject", type="string", nullable=true)
	 * @Assert\NotBlank()
	 */
	private $subject;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="message", type="string", nullable=true)
	 * @Assert\NotBlank()
	 */
	private $message;

	/**
	 * Set subject
	 *
	 * @param string $subject
	 *
	 * @return FormTranslation
	 */
	public function setSubject(?string $subject): FormTranslation
	{
		$this->subject = $subject;

		return $this;
	}

	/**
	 * Get subject
	 *
	 * @return string
	 */
	public function getSubject(): ?string
	{
		return $this->subject;
	}

	/**
	 * Get message
	 *
	 * @param string $message
	 *
	 * @return FormTranslation
	 */
	public function setMessage(?string $message): FormTranslation
	{
		$this->message = $message;

		return $this;
	}

	/**
	 * Get message
	 *
	 * @return string
	 */
	public function getMessage(): ?string
	{
		return $this->message;
	}
}