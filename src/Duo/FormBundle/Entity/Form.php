<?php

namespace Duo\FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\CoreBundle\Entity\TranslationInterface;
use Duo\NodeBundle\Entity\AbstractNode;
use Duo\PageBundle\Entity\PageInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(
 *     name="duo_form",
 *     uniqueConstraints={
 *		   @ORM\UniqueConstraint(name="name_uniq", columns={ "name" })
 *	   },
 *     indexes={
 *		   @ORM\Index(name="name_idx", columns={ "name" })
 *	   }
 * )
 * @ORM\Entity()
 * @UniqueEntity(fields={ "name" }, message="duo.form.errors.name_used")
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
	 * @var bool
	 *
	 * @ORM\Column(name="keep_submissions", type="boolean", options={ "default" = 0 })
	 */
	protected $keepSubmissions = false;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="send_submissions_to", type="string", nullable=true)
	 * @Assert\Length(min="0")
	 * @Assert\Email()
	 */
	protected $sendSubmissionsTo;

	/**
	 * @var PageInterface
	 *
	 * @ORM\ManyToOne(targetEntity="Duo\PageBundle\Entity\PageInterface")
	 * @ORM\JoinColumn(name="page_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $page;

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
	 * Set keepSubmissions
	 *
	 * @param bool $keepSubmissions
	 *
	 * @return Form
	 */
	public function setKeepSubmissions(bool $keepSubmissions): Form
	{
		$this->keepSubmissions = $keepSubmissions;

		return $this;
	}

	/**
	 * Get keepSubmissions
	 *
	 * @return bool
	 */
	public function getKeepSubmissions(): bool
	{
		return $this->keepSubmissions;
	}

	/**
	 * Set sendSubmissionsTo
	 *
	 * @param string $sendSubmissionsTo
	 *
	 * @return Form
	 */
	public function setSendSubmissionsTo(string $sendSubmissionsTo = null): Form
	{
		$this->sendSubmissionsTo = $sendSubmissionsTo;

		return $this;
	}

	/**
	 * Get sendSubmissionsTo
	 *
	 * @return string
	 */
	public function getSendSubmissionsTo(): ?string
	{
		return $this->sendSubmissionsTo;
	}

	/**
	 * Set page
	 * 
	 * @param PageInterface $page
	 *
	 * @return Form
	 */
	public function setPage(PageInterface $page = null): Form
	{
		$this->page = $page;

		return $this;
	}

	/**
	 * Get page
	 *
	 * @return PageInterface
	 */
	public function getPage(): ?PageInterface
	{
		return $this->page;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @return FormTranslation|TranslationInterface
	 */
	public function translate(string $locale = null, bool $fallback = true): FormTranslation
	{
		return $this->doTranslate($locale, $fallback);
	}
}