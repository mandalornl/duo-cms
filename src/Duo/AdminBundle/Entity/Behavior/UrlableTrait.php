<?php

namespace Duo\AdminBundle\Entity\Behavior;

use Doctrine\ORM\Mapping as ORM;

trait UrlableTrait
{
	/**
	 * @var string
	 *
	 * @ORM\Column(name="url", type="string", nullable=false)
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