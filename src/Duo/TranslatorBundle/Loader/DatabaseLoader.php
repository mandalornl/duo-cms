<?php

namespace Duo\TranslatorBundle\Loader;

use Doctrine\ORM\EntityManagerInterface;
use Duo\TranslatorBundle\Entity\Entry;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\MessageCatalogue;

class DatabaseLoader implements LoaderInterface
{
	/**
	 * @var EntityManagerInterface
	 */
	private $manager;

	/**
	 * EntryLoader constructor
	 *
	 * @param EntityManagerInterface $manager
	 */
	public function __construct(EntityManagerInterface $manager)
	{
		$this->manager = $manager;
	}

	/**
	 * {@inheritdoc}
	 */
	public function load($resource, $locale, $domain = 'messages'): MessageCatalogue
	{
		$catalogue = new MessageCatalogue($locale);

		/**
		 * @var Entry[] $entities
		 */
		$entities = $this->manager->getRepository(Entry::class)->findBy([
			'domain' => $domain
		]);

		foreach ($entities as $entity)
		{
			$catalogue->set($entity->getKeyword(), $entity->translate($locale)->getText(), $domain);
		}

		$catalogue->addResource(new FileResource($resource));

		return $catalogue;
	}
}