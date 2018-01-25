<?php

namespace Duo\FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\BehaviorBundle\Entity\IdTrait;
use Duo\BehaviorBundle\Entity\TimeStampInterface;
use Duo\BehaviorBundle\Entity\TimeStampTrait;

/**
 * @ORM\Table(name="form_submission")
 * @ORM\Entity()
 */
class FormSubmission implements TimeStampInterface
{
	use IdTrait;
	use TimeStampTrait;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="name", type="string", nullable=true)
	 */
	private $name;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="locale", type="string", length=5, nullable=true)
	 */
	private $locale;

	/**
	 * @var array
	 *
	 * @ORM\Column(name="data", type="json", nullable=true)
	 */
	private $data;

	/**
	 * @var Form
	 *
	 * @ORM\ManyToOne(targetEntity="Duo\FormBundle\Entity\Form")
	 * @ORM\JoinColumns({
	 * 	   @ORM\JoinColumn(name="form_id", referencedColumnName="id", onDelete="SET NULL")
	 * })
	 */
	private $form;

	/**
	 * Set name
	 *
	 * @param string $name
	 *
	 * @return FormSubmission
	 */
	public function setName(string $name = null): FormSubmission
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName(): ?string
	{
		return $this->name;
	}

	/**
	 * Set locale
	 *
	 * @param string $locale
	 *
	 * @return FormSubmission
	 */
	public function setLocale(string $locale = null): FormSubmission
	{
		$this->locale = $locale;

		return $this;
	}

	/**
	 * Get locale
	 *
	 * @return string
	 */
	public function getLocale(): ?string
	{
		return $this->locale;
	}

	/**
	 * Set data
	 *
	 * @param array $data
	 *
	 * @return FormSubmission
	 */
	public function setData(array $data): FormSubmission
	{
		$this->data = $data;

		return $this;
	}

	/**
	 * Get data
	 *
	 * @return array
	 */
	public function getData(): array
	{
		return $this->data;
	}

	/**
	 * Set form
	 *
	 * @param Form $form
	 *
	 * @return FormSubmission
	 */
	public function setForm(Form $form = null): FormSubmission
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
}