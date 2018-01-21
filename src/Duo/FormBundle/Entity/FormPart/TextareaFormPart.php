<?php

namespace Duo\FormBundle\Entity\FormPart;

use Doctrine\ORM\Mapping as ORM;
use Duo\FormBundle\Entity\AbstractFormPart;
use Duo\FormBundle\Form\FormPart\TextareaFormPartType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * @ORM\Table(name="form_part_textarea")
 * @ORM\Entity()
 */
class TextareaFormPart extends AbstractFormPart
{
	/**
	 * {@inheritdoc}
	 */
	public function getFormType(): string
	{
		return TextareaType::class;
	}

	/**
	 * Get form type
	 *
	 * @return string
	 */
	public function getPartFormType(): string
	{
		return TextareaFormPartType::class;
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