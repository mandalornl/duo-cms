<?php

namespace Duo\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

trait UrlTrait
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
	public function setUrl(string $url = null): UrlInterface
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