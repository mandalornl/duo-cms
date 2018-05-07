<?php

namespace Duo\FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\BehaviorBundle\Entity\IdInterface;
use Duo\BehaviorBundle\Entity\IdTrait;
use Duo\BehaviorBundle\Entity\TimestampInterface;
use Duo\BehaviorBundle\Entity\TimestampTrait;

/**
 * @ORM\Table(name="duo_form_result")
 * @ORM\Entity()
 */
class FormResult implements IdInterface, TimestampInterface
{
	use IdTrait;
	use TimestampTrait;

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
	 * @ORM\JoinColumn(name="form_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	private $form;

	/**
	 * Set name
	 *
	 * @param string $name
	 *
	 * @return FormResult
	 */
	public function setName(string $name = null): FormResult
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
	 * @return FormResult
	 */
	public function setLocale(string $locale = null): FormResult
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
	 * @return FormResult
	 */
	public function setData(array $data): FormResult
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
	 * @return FormResult
	 */
	public function setForm(Form $form = null): FormResult
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