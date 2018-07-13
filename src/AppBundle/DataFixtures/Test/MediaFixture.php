<?php

namespace AppBundle\DataFixtures\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Duo\MediaBundle\Entity\Media;
use Duo\MediaBundle\Helper\UploadHelper;
use Duo\SecurityBundle\Entity\User;
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
	 */
	public function load(ObjectManager $manager): void
	{
		if (($media = $manager->getRepository(Media::class)->findOneBy(['name' => 'foobar.jpg'])) !== null)
		{
			return;
		}

		/**
		 * @var User $user
		 */
		$user = $this->getReference('user');

		/**
		 * @var Taxonomy $taxonomy
		 */
		$taxonomy = $this->getReference('taxonomy_files');

		$filename = __DIR__ . '/example.jpg';

		list($width, $height, $mimeType) = @getimagesize($filename);

		$size = @filesize($filename);

		$info = pathinfo($filename);

		$uuid = UploadHelper::getUuid();
		$relativePath = $this->container->getParameter('duo.media.relative_upload_path');

		$media = (new Media())
			->setName($info['basename'])
			->setUuid($uuid)
			->setUrl("{$relativePath}/{$uuid}/{$info['basename']}")
			->setMimeType(image_type_to_mime_type($mimeType))
			->setSize($size)
			->setMetadata([
				'width' => $width,
				'height' => $height,
				'basename' => $info['basename'],
				'extension' => $info['extension'],
				'filename' => $info['filename']
			]);

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