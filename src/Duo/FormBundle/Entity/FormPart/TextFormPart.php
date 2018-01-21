<?php

namespace Duo\FormBundle\Entity\FormPart;

use Doctrine\ORM\Mapping as ORM;
use Duo\FormBundle\Entity\AbstractFormPart;
use Duo\FormBundle\Form\FormPart\TextFormPartType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * @ORM\Table(name="form_part_text")
 * @ORM\Entity()
 */
class TextFormPart extends AbstractFormPart
{
	/**
	 * {@inheritdoc}
	 */
	public function getFormType(): string
	{
		return TextType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPartFormType(): string
	{
		return TextFormPartType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getView(): string
	{
		// TODO: Implement getView() method.
		return '';
	}
}