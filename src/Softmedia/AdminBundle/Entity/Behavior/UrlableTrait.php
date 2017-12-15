<?php

namespace Softmedia\AdminBundle\Entity\Behavior;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait UrlableTrait
{
	/**
	 * @var string
	 *
	 * @ORM\Column(name="url", type="string", nullable=true)
	 * @Assert\NotBlank()
	 */
	protected $url;

	/**
	 * {@inheritdoc}
	 */
	public function setUrl(string $url = null): UrlableInterface
	{
		$this->url = $url;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getUrl(): ?string
	{
		return $this->url;
	}
}