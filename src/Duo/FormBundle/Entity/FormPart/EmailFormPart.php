<?php

namespace Duo\FormBundle\Entity\FormPart;

use Doctrine\ORM\Mapping as ORM;
use Duo\FormBundle\Form\FormPart\EmailFormPartType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @ORM\Table(name="duo_form_part_email")
 * @ORM\Entity()
 */
class EmailFormPart extends AbstractTextFormPart
{
	/**
	 * {@inheritdoc}
	 */
	public function getFormType(): string
	{
		return EmailType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFormOptions(): array
	{
		return [
			'constraints' => [
				$this->getRequired() ? ($this->getErrorMessage() ? new NotBlank([
					'message' => $this->getErrorMessage()
				]) : new NotBlank()) : new Length([
					'min' => 0
				]),
				$this->getErrorMessage() ? new Email([
					'message' => $this->getErrorMessage()
				]) : new Email()
			]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPartFormType(): string
	{
		return EmailFormPartType::class;
	}
}