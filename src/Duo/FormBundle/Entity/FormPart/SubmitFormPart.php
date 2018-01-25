<?php

namespace Duo\FormBundle\Entity\FormPart;

use Doctrine\ORM\Mapping as ORM;
use Duo\FormBundle\Entity\AbstractFormPart;
use Duo\FormBundle\Form\FormPart\SubmitFormPartType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * @ORM\Table(name="form_part_submit")
 * @ORM\Entity()
 */
class SubmitFormPart extends AbstractFormPart
{
	/**
	 * {@inheritdoc}
	 */
	public function getFormType(): string
	{
		return SubmitType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPartFormType(): string
	{
		return SubmitFormPartType::class;
	}
}