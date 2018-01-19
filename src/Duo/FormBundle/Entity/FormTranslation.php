<?php

namespace Duo\FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\NodeBundle\Entity\AbstractNodeTranslation;
use Duo\PartBundle\Entity\NodePartInterface;
use Duo\PartBundle\Entity\NodePartTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="form_translation")
 * @ORM\Entity()
 */
class FormTranslation extends AbstractNodeTranslation implements NodePartInterface
{
	use NodePartTrait;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="subject", type="string", nullable=false)
	 * @Assert\NotBlank()
	 */
	protected $subject;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="message", type="string", nullable=false)
	 * @Assert\NotBlank()
	 */
	protected $message;

	/**
	 * Set subject
	 *
	 * @param string $subject
	 *
	 * @return FormTranslation
	 */
	public function setSubject(string $subject = null): FormTranslation
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
	public function setMessage(string $message = null): FormTranslation
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