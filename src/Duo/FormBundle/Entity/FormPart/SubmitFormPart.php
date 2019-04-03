<?php

namespace Duo\FormBundle\Entity\FormPart;

use Doctrine\ORM\Mapping as ORM;
use Duo\FormBundle\Form\FormPart\SubmitFormPartType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * @ORM\Table(name="duo_form_part_submit")
 * @ORM\Entity()
 */
class SubmitFormPart extends AbstractFormPart
{
	/**
	 * {@inheritDoc}
	 */
	public function getFormType(): string
	{
		return SubmitType::class;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getPartFormType(): string
	{
		return SubmitFormPartType::class;
	}
}
