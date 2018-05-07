<?php

namespace AppBundle\DataFixtures\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Duo\MediaBundle\Entity\File;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FileFixture extends Fixture implements DependentFixtureInterface, ContainerAwareInterface
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
		if (($file = $manager->getRepository(File::class)->findOneBy(['name' => 'foobar.jpg'])) !== null)
		{
			return;
		}

		$user = $this->getReference('user');
		$folder = $this->getReference('folder');

		$uuid = md5(uniqid());
		$path = $this->container->getParameter('duo.media.relative_upload_path');

		$file = (new File())
			->setName('foobar.jpg')
			->setUuid($uuid)
			->setUrl("{$path}/{$uuid}/foobar.jpg")
			->setMimeType('image/jpeg')
			->setSize(pow(1024, 2))
			->setMetadata([
				'width' => 1920,
				'height' => 1080,
				'basename' => 'foobar',
				'extension' => 'jpg',
				'filename' => 'foobar.jpg'
			])
			->setFolder($folder)
			->setCreatedBy($user);

		$manager->persist($file);
		$manager->flush();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDependencies(): array
	{
		return [
			FolderFixture::class
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