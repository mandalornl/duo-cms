<?php

namespace AppBundle\DataFixtures\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Duo\MediaBundle\Entity\Media;
use Duo\SecurityBundle\Entity\UserInterface;
use Duo\TaxonomyBundle\Entity\Taxonomy;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MediaFixture extends Fixture implements DependentFixtureInterface, ContainerAwareInterface
{
	/**
	 * @var ContainerInterface
	 */
	private $container;

	/**
	 * {@inheritDoc}
	 *
	 * @throws \Throwable
	 */
	public function load(ObjectManager $manager): void
	{
		if ($manager->getRepository(Media::class)->count([]))
		{
			return;
		}

		/**
		 * @var UserInterface $user
		 */
		$user = $this->getReference('user');

		/**
		 * @var Taxonomy $taxonomy
		 */
		$taxonomy = $this->getReference('taxonomy-files');

		foreach ([
			'jpg' => __DIR__ . '/example.jpg',
			'pdf' => __DIR__ . '/example.pdf'
		 ] as $type => $filename)
		{
			$size = @filesize($filename);

			$info = pathinfo($filename);

			$relativePath = $this->container->getParameter('duo_media.relative_path');

			$mimeType = mime_content_type($filename);

			$metadata = [
				'basename' => $info['basename'],
				'extension' => $info['extension'],
				'filename' => $info['filename']
			];

			if (strpos($mimeType, 'image/') === 0)
			{
				list($width, $height) = @getimagesize($filename);

				$metadata = array_merge($metadata, [
					'width' => $width,
					'height' => $height
				]);
			}

			$media = (new Media())
				->setName($info['basename'])
				->setMimeType($mimeType)
				->setSize($size)
				->setMetadata($metadata);

			$media->setUrl("{$relativePath}/{$media->getUuid()}/{$info['basename']}");

			$media->setCreatedBy($user);
			$media->addTaxonomy($taxonomy);

			$manager->persist($media);
			$manager->flush();

			$absolutePath = $this->container->getParameter('duo_media.absolute_path');

			if (!is_dir("{$absolutePath}/{$media->getUuid()}"))
			{
				mkdir("{$absolutePath}/{$media->getUuid()}");
			}

			copy($filename, "{$absolutePath}/{$media->getUuid()}/{$info['basename']}");

			$this->addReference("media-{$type}", $media);
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function getDependencies(): array
	{
		return [
			TaxonomyFixture::class
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public function setContainer(ContainerInterface $container = null): void
	{
		$this->container = $container;
	}
}
