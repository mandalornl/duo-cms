<?php

namespace AppBundle\DataFixtures\Test;

use AppBundle\Entity\PagePart\FormPagePart;
use AppBundle\Entity\PagePart\HeadingPagePart;
use AppBundle\Entity\PagePart\ImageCropPagePart;
use AppBundle\Entity\PagePart\WYSIWYGPagePart;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Duo\FormBundle\Entity\Form;
use Duo\MediaBundle\Entity\ImageCrop;
use Duo\MediaBundle\Entity\Media;
use Duo\PageBundle\Entity\PageInterface;
use Duo\SecurityBundle\Entity\UserInterface;

class PageFixture extends Fixture implements DependentFixtureInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function load(ObjectManager $manager): void
	{
		if ($manager->getRepository(PageInterface::class)->count([]))
		{
			return;
		}

		/**
		 * @var UserInterface $user
		 */
		$user = $this->getReference('user');

		/**
		 * @var Form $form
		 */
		$form = $this->getReference('form');

		/**
		 * @var Media $media
		 */
		$media = $this->getReference('media-jpg');

		$parent = null;

		// create home
		$page = $manager->getClassMetadata(PageInterface::class)->getReflectionClass()->newInstance();
		$page->setName('home');
		$page->setParent($parent);
		$page->setCreatedBy($user);

		$page->translate('nl')
			->setTitle('Welkom')
			->publish()
			->setPublishedBy($user)
			->setSlug('')
			->addPart(
				(new HeadingPagePart())
					->setType('h1')
					->setValue('Welkom')
					->setWeight(0)
					->setSection('main')
					->setCreatedBy($user)
			)
			->addPart(
				(new WYSIWYGPagePart())
					->setValue('<p>Dit is de homepagina.</p>')
					->setWeight(1)
					->setSection('main')
					->setCreatedBy($user)
			)
			->addPart(
				(new FormPagePart())
					->setForm($form)
					->setWeight(2)
					->setSection('main')
					->setCreatedBy($user)
			)
			->addPart(
				(new ImageCropPagePart())
					->setWeight(3)
					->setSection('widgets')
					->setCreatedBy($user)
					->setImageCrop(
						(new ImageCrop())
							->setCrop('0;0;0.75;1')
							->setRatio('4:3')
							->setMedia($media)
							->setCreatedBy($user)
					)
					->setAltText('Test afbeelding')
			);

		$page->translate('en')
			->setTitle('Welcome')
			->publish()
			->setPublishedBy($user)
			->setSlug('')
			->addPart(
				(new HeadingPagePart())
					->setType('h1')
					->setValue('Welcome')
					->setWeight(0)
					->setSection('main')
					->setCreatedBy($user)
			)
			->addPart(
				(new WYSIWYGPagePart())
					->setValue('<p>This is the home page.</p>')
					->setWeight(1)
					->setSection('main')
					->setCreatedBy($user)
			)
			->addPart(
				(new FormPagePart())
					->setForm($form)
					->setWeight(2)
					->setSection('main')
					->setCreatedBy($user)
			)
			->addPart(
				(new ImageCropPagePart())
					->setWeight(3)
					->setSection('widgets')
					->setCreatedBy($user)
					->setImageCrop(
						(new ImageCrop())
							->setCrop('0;0;0.75;1')
							->setRatio('4:3')
							->setMedia($media)
							->setCreatedBy($user)
					)
					->setAltText('Test image')
			);

		$page->mergeNewTranslations();

		$manager->persist($page);
		$manager->flush();

		// create news
		$page = $manager->getClassMetadata(PageInterface::class)->getReflectionClass()->newInstance();
		$page->setName('news');
		$page->setCreatedBy($user);
		$page->setWeight(1);

		$page->translate('nl')
			->setTitle('Nieuws')
			->publish()
			->setPublishedBy($user)
			->addPart(
				(new HeadingPagePart())
					->setType('h1')
					->setValue('Nieuws')
					->setWeight(0)
					->setSection('main')
					->setCreatedBy($user)
			)
			->addPart(
				(new WYSIWYGPagePart())
					->setValue('<p>Dit is nieuws.</p>')
					->setWeight(1)
					->setSection('main')
					->setCreatedBy($user)
			);

		$page->translate('en')
			->setTitle('News')
			->publish()
			->setPublishedBy($user)
			->addPart(
				(new HeadingPagePart())
					->setType('h1')
					->setValue('News')
					->setWeight(0)
					->setSection('main')
					->setCreatedBy($user)
			)
			->addPart(
				(new WYSIWYGPagePart())
					->setValue('<p>This is news.</p>')
					->setWeight(1)
					->setSection('main')
					->setCreatedBy($user)
			);

		$page->mergeNewTranslations();

		$manager->persist($page);
		$manager->flush();

		$parent = $page;

		// create article
		$page = $manager->getClassMetadata(PageInterface::class)->getReflectionClass()->newInstance();
		$page->setName('article');
		$page->setParent($parent);
		$page->setCreatedBy($user);

		$page->translate('nl')
			->setTitle('Artikel')
			->publish()
			->setPublishedBy($user)
			->addPart(
				(new HeadingPagePart())
					->setType('h1')
					->setValue('Artikel')
					->setWeight(0)
					->setSection('main')
					->setCreatedBy($user)
			)
			->addPart(
				(new WYSIWYGPagePart())
					->setValue('<p>Dit is een artikel.</p>')
					->setWeight(1)
					->setSection('main')
					->setCreatedBy($user)
			);

		$page->translate('en')
			->setTitle('Article')
			->publish()
			->setPublishedBy($user)
			->addPart(
				(new HeadingPagePart())
					->setType('h1')
					->setValue('Article')
					->setWeight(0)
					->setSection('main')
					->setCreatedBy($user)
			)
			->addPart(
				(new WYSIWYGPagePart())
					->setValue('<p>This is an article.</p>')
					->setWeight(1)
					->setSection('main')
					->setCreatedBy($user)
			);

		$page->mergeNewTranslations();

		$manager->persist($page);
		$manager->flush();

		// create more dummy
		for ($i = 1; $i <= 10; $i++)
		{
			$page = $manager->getClassMetadata(PageInterface::class)->getReflectionClass()->newInstance();
			$page->setName("lorem-ipsum-{$i}");
			$page->setCreatedBy($user);
			$page->setWeight($i + 1);

			$page->translate('nl')
				->setTitle("Lorem ipsum {$i}")
				->publish()
				->setPublishedBy($user)
				->addPart(
					(new HeadingPagePart())
						->setType('h1')
						->setValue("Lorem ipsum {$i}")
						->setWeight(0)
						->setSection('main')
						->setCreatedBy($user)
				)
				->addPart(
					(new WYSIWYGPagePart())
						->setValue("<p>Dit is lorem ipsum {$i}.</p>")
						->setWeight(1)
						->setSection('main')
						->setCreatedBy($user)
				);

			$page->translate('en')
				->setTitle("Lorem ipsum {$i}")
				->publish()
				->setPublishedBy($user)
				->addPart(
					(new HeadingPagePart())
						->setType('h1')
						->setValue("Lorem ipsum {$i}")
						->setWeight(0)
						->setSection('main')
						->setCreatedBy($user)
				)
				->addPart(
					(new WYSIWYGPagePart())
						->setValue("<p>This is lorem ipsum {$i}.</p>")
						->setWeight(1)
						->setSection('main')
						->setCreatedBy($user)
				);

			$page->mergeNewTranslations();

			$manager->persist($page);
		}

		$manager->flush();
		$manager->clear();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDependencies(): array
	{
		return [
			FormFixture::class,
			MediaFixture::class
		];
	}
}