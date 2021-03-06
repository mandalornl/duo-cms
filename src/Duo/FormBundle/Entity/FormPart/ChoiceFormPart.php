<?php

namespace Duo\FormBundle\Entity\FormPart;

use Doctrine\ORM\Mapping as ORM;
use Duo\FormBundle\Form\FormPart\ChoiceFormPartType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * @ORM\Table(name="duo_form_part_choice")
 * @ORM\Entity()
 */
class ChoiceFormPart extends AbstractChoiceFormPart
{
	/**
	 * {@inheritDoc}
	 */
	public function getFormType(): string
	{
		return ChoiceType::class;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getPartFormType(): string
	{
		return ChoiceFormPartType::class;
	}
}
