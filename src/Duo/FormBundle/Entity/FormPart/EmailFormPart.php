<?php

namespace Duo\FormBundle\Entity\FormPart;

use Doctrine\ORM\Mapping as ORM;
use Duo\FormBundle\Entity\AbstractFormPart;
use Duo\FormBundle\Form\FormPart\EmailFormPartType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

/**
 * @ORM\Table(name="form_part_email")
 * @ORM\Entity()
 */
class EmailFormPart extends AbstractFormPart
{
	/**
	 * Get type
	 *
	 * @return string
	 */
	public function getFormType(): string
	{
		return EmailType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPartFormType(): string
	{
		return EmailFormPartType::class;
	}

	/**
	 * Get view
	 *
	 * @return string
	 */
	public function getView(): string
	{
		// TODO: Implement getView() method.
		return '';
	}
}