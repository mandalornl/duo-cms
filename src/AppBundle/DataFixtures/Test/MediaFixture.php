<?php

namespace AppBundle\DataFixtures\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Duo\MediaBundle\Entity\Media;
use Duo\MediaBundle\Helper\UploadHelper;
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
	 * {@inheritdoc}
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
		$taxonomy = $this->getReference('taxonomy_files');

		foreach ([
			'jpg' => __DIR__ . '/example.jpg',
			'pdf' => __DIR__ . '/example.pdf'
		 ] as $type => $filename)
		{
			$size = @filesize($filename);

			$info = pathinfo($filename);

			$uuid = UploadHelper::getUuid();
			$relativePath = $this->container->getParameter('duo.media.relative_upload_path');

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
				->setUuid($uuid)
				->setUrl("{$relativePath}/{$uuid}/{$info['basename']}")
				->setMimeType($mimeType)
				->setSize($size)
				->setMetadata($metadata);

			$media->setCreatedBy($user);
			$media->addTaxonomy($taxonomy);

			$manager->persist($media);
			$manager->flush();

			$absolutePath = $this->container->getParameter('duo.media.absolute_upload_path');

			if (!is_dir("{$absolutePath}/{$uuid}"))
			{
				mkdir("{$absolutePath}/{$uuid}");
			}

			copy($filename, "{$absolutePath}/{$uuid}/{$info['basename']}");

			$this->addReference("media-{$type}", $media);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDependencies(): array
	{
		return [
			TaxonomyFixture::class
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function setContainer(ContainerInterface $container = null): void
	{
		$this->container = $container;
	}
}