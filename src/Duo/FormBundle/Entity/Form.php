<?php

namespace Duo\FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\CoreBundle\Entity\TranslationInterface;
use Duo\NodeBundle\Entity\AbstractNode;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="duo_form")
 * @ORM\Entity(repositoryClass="Duo\FormBundle\Repository\FormRepository")
 */
class Form extends AbstractNode
{
	/**
	 * @var string
	 *
	 * @ORM\Column(name="email_from", type="string", nullable=false)
	 * @Assert\NotBlank()
	 * @Assert\Email()
	 */
	protected $emailFrom;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="email_to", type="string", nullable=false)
	 * @Assert\NotBlank()
	 * @Assert\Email()
	 */
	protected $emailTo;

	/**
	 * Set emailFrom
	 *
	 * @param string $emailFrom
	 *
	 * @return Form
	 */
	public function setEmailFrom(string $emailFrom = null): Form
	{
		$this->emailFrom = $emailFrom;

		return $this;
	}

	/**
	 * Get emailFrom
	 *
	 * @return string
	 */
	public function getEmailFrom(): ?string
	{
		return $this->emailFrom;
	}

	/**
	 * Set emailTo
	 *
	 * @param string $emailTo
	 *
	 * @return Form
	 */
	public function setEmailTo(string $emailTo = null): Form
	{
		$this->emailTo = $emailTo;

		return $this;
	}

	/**
	 * Get emailTo
	 *
	 * @return string
	 */
	public function getEmailTo(): ?string
	{
		return $this->emailTo;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @return FormTranslation|TranslationInterface
	 */
	public function translate(string $locale = null, bool $fallback = true)
	{
		return $this->doTranslate($locale, $fallback);
	}
}