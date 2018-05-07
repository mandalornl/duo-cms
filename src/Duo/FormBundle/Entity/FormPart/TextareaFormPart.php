<?php

namespace Duo\FormBundle\Entity\FormPart;

use Doctrine\ORM\Mapping as ORM;
use Duo\FormBundle\Entity\AbstractTextFormPart;
use Duo\FormBundle\Form\FormPart\TextareaFormPartType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * @ORM\Table(name="duo_form_part_textarea")
 * @ORM\Entity()
 */
class TextareaFormPart extends AbstractTextFormPart
{
	/**
	 * {@inheritdoc}
	 */
	public function getFormType(): string
	{
		return TextareaType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPartFormType(): string
	{
		return TextareaFormPartType::class;
	}
}