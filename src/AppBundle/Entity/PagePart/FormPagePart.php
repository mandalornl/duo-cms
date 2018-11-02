<?php

namespace AppBundle\Entity\PagePart;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Form\PagePart\FormPagePartType;
use Duo\FormBundle\Entity\Form;
use Duo\PageBundle\Entity\PagePart\AbstractPagePart;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="duo_page_part_form")
 * @ORM\Entity()
 */
class FormPagePart extends AbstractPagePart
{
	/**
	 * @var Form
	 *
	 * @ORM\ManyToOne(targetEntity="Duo\FormBundle\Entity\Form")
	 * @ORM\JoinColumn(name="form_id", referencedColumnName="id", onDelete="SET NULL")
	 * @Assert\NotBlank()
	 */
	private $form;

	/**
	 * Set form
	 *
	 * @param Form $form
	 *
	 * @return FormPagePart
	 */
	public function setForm(?Form $form): FormPagePart
	{
		$this->form = $form;

		return $this;
	}

	/**
	 * Get form
	 *
	 * @return Form
	 */
	public function getForm(): ?Form
	{
		return $this->form;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPartFormType(): string
	{
		return FormPagePartType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getView(): string
	{
		return '@App/PagePart/Form/view.html.twig';
	}
}