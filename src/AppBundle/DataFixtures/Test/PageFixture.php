<?php

namespace AppBundle\DataFixtures\Test;

use AppBundle\Entity\PagePart\FormPagePart;
use AppBundle\Entity\PagePart\HeadingPagePart;
use AppBundle\Entity\PagePart\WYSIWYGPagePart;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Duo\PageBundle\Entity\PageInterface;

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

		$repository = $manager->getRepository(PageInterface::class);

		$user = $this->getReference('user');
		$form = $this->getReference('form');

		$parent = null;

		if (($page = $repository->findOneBy(['name' => 'home'])) === null)
		{
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
						->setCreatedBy($user)
				)
				->addPart(
					(new WYSIWYGPagePart())
						->setValue('<p>Dit is de homepagina.</p>')
						->setCreatedBy($user)
				)
				->addPart(
					(new FormPagePart())
						->setForm($form)
						->setCreatedBy($user)
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
						->setCreatedBy($user)
				)
				->addPart(
					(new WYSIWYGPagePart())
						->setValue('<p>This is the home page.</p>')
						->setCreatedBy($user)
				)
				->addPart(
					(new FormPagePart())
						->setForm($form)
						->setCreatedBy($user)
				);

			$page->mergeNewTranslations();

			$manager->persist($page);
			$manager->flush();

			$parent = $page;
		}

		if (($page = $repository->findOneBy(['name' => 'nieuws'])) === null)
		{
			$page = $manager->getClassMetadata(PageInterface::class)->getReflectionClass()->newInstance();
			$page->setName('news');
			$page->setParent($parent);
			$page->setCreatedBy($user);

			$page->translate('nl')
				->setTitle('Nieuws')
				->publish()
				->setPublishedBy($user)
				->addPart(
					(new HeadingPagePart())
						->setType('h1')
						->setValue('Nieuws')
						->setCreatedBy($user)
				)
				->addPart(
					(new WYSIWYGPagePart())
						->setValue('<p>Dit is nieuws.</p>')
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
						->setCreatedBy($user)
				)
				->addPart(
					(new WYSIWYGPagePart())
						->setValue('<p>This is news.</p>')
						->setCreatedBy($user)
				);

			$page->mergeNewTranslations();

			$manager->persist($page);
			$manager->flush();

			$parent = $page;
		}

		if (($page = $repository->findOneBy(['name' => 'article'])) === null)
		{
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
						->setCreatedBy($user)
				)
				->addPart(
					(new WYSIWYGPagePart())
						->setValue('<p>Dit is een artikel.</p>')
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
						->setCreatedBy($user)
				)
				->addPart(
					(new WYSIWYGPagePart())
						->setValue('<p>This is an article.</p>')
						->setCreatedBy($user)
				);

			$page->mergeNewTranslations();

			$manager->persist($page);
			$manager->flush();
		}

		if (($page = $repository->findOneBy(['name' => 'foobar-1'])) === null)
		{
			for ($i = 1; $i <= 10; $i++)
			{
				$page = $manager->getClassMetadata(PageInterface::class)->getReflectionClass()->newInstance();
				$page->setName("foobar-{$i}");
				$page->setCreatedBy($user);
				$page->setWeight($i);

				$page->translate('nl')
					->setTitle("Foobar {$i}")
					->publish()
					->setPublishedBy($user)
					->addPart(
						(new HeadingPagePart())
							->setType('h1')
							->setValue("Foobar {$i}")
							->setCreatedBy($user)
					)
					->addPart(
						(new WYSIWYGPagePart())
							->setValue("<p>Dit is foobar {$i}.</p>")
							->setCreatedBy($user)
					);

				$page->translate('en')
					->setTitle("Foobar {$i}")
					->publish()
					->setPublishedBy($user)
					->addPart(
						(new HeadingPagePart())
							->setType('h1')
							->setValue("Foobar {$i}")
							->setCreatedBy($user)
					)
					->addPart(
						(new WYSIWYGPagePart())
							->setValue("<p>This is foobar {$i}.</p>")
							->setCreatedBy($user)
					);

				$page->mergeNewTranslations();

				$manager->persist($page);
			}

			$manager->flush();
		}

		$manager->clear();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDependencies(): array
	{
		return [
			FormFixture::class
		];
	}
}