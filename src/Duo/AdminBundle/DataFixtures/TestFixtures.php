<?php

namespace Duo\AdminBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Duo\NodeBundle\Entity\Page;
use Duo\PagePartBundle\Entity\HeadingPagePart;
use Duo\PagePartBundle\Entity\WYSIWYGPagePart;
use Duo\SecurityBundle\Entity\Group;
use Duo\SecurityBundle\Entity\Role;
use Duo\SecurityBundle\Entity\User;
use Duo\TaxonomyBundle\Entity\Taxonomy;

class TestFixtures extends Fixture
{
	/**
	 * @var ObjectManager
	 */
	private $manager;

	/**
	 * {@inheritdoc}
	 */
	public function load(ObjectManager $manager)
	{
		$this->manager = $manager;

		$this->createRoles();
		$this->createGroups();
		$this->createUser();
		$this->createTaxonomies();
		$this->createPages();
	}

	/**
	 * Create roles
	 */
	private function createRoles()
	{
		if (count($this->manager->getRepository(Role::class)->findAll()))
		{
			return;
		}

		foreach ([
			'Super Administrator' => 'ROLE_SUPER_ADMIN',
			'Administrator' => 'ROLE_ADMIN',
			'User' => 'ROLE_USER',
			'Anonymous' => 'IS_AUTHENTICATED_ANONYMOUSLY',
			'Can view' => 'ROLE_CAN_VIEW',
			'Can edit' => 'ROLE_CAN_EDIT',
			'Can delete' => 'ROLE_CAN_DELETE',
			'Can publish' => 'ROLE_CAN_PUBLISH'
		 ] as $name => $roleName)
		{
			$role = (new Role())
				->setName($name)
				->setRole($roleName);

			$roles[$roleName] = &$role;

			$this->manager->persist($role);
		}

		$this->manager->flush();
	}

	/**
	 * Create groups
	 */
	private function createGroups()
	{
		if (count($this->manager->getRepository(Group::class)->findAll()))
		{
			return;
		}

		$roles = $this->manager->getRepository(Role::class)->findAll();
		foreach ($roles as $index => $role)
		{
			$roles[$role->getRole()] = $role;
			unset($roles[$index]);
		}

		foreach ([
			'Super Administrators' => [
				'ROLE_SUPER_ADMIN',
				'ROLE_ADMIN',
				'ROLE_USER',
				'ROLE_CAN_VIEW',
				'ROLE_CAN_EDIT',
				'ROLE_CAN_DELETE',
				'ROLE_CAN_PUBLISH'
			],
			'Administrators' => [
				'ROLE_ADMIN',
				'ROLE_USER',
				'ROLE_CAN_VIEW',
				'ROLE_CAN_EDIT',
				'ROLE_CAN_DELETE',
				'ROLE_CAN_PUBLISH'
			],
			'Users' => [
				'ROLE_USER',
				'ROLE_CAN_VIEW'
			],
			'Guests' => [
				'IS_AUTHENTICATED_ANONYMOUSLY'
			]
		 ] as $groupName => $roleNames)
		{
			$group = (new Group())
				->setName($groupName);

			foreach ($roleNames as $roleName)
			{
				$group->addRole($roles[$roleName]);
			}

			$this->manager->persist($group);
		}

		$this->manager->flush();
	}

	/**
	 * Create user
	 */
	private function createUser()
	{
		if (($user = $this->manager->getRepository(User::class)->findOneBy(['username' => 'admin'])) !== null)
		{
			return;
		}

		$group = $this->manager->getRepository(Group::class)->findOneBy(['name' => 'Super Administrators']);

		$user = (new User())
			->setName('Admin')
			->setEmail('admin@duocms.nl')
			->setActive(true)
			->addGroup($group);

		$user
			->setUsername('admin')
			->setPlainPassword('admin');

		$user->setCreatedBy($user);

		$this->manager->persist($user);
		$this->manager->flush();
	}

	/**
	 * Create taxonomies
	 */
	private function createTaxonomies()
	{
		if (count($taxonomies = $this->manager->getRepository(Taxonomy::class)->findAll()))
		{
			return;
		}

		$user = $this->manager->getRepository(User::class)->findOneBy(['username' => 'admin']);

		$taxonomy = (new Taxonomy());
		$taxonomy->setCreatedBy($user);
		$taxonomy->translate('nl')->setName('Pagina');
		$taxonomy->translate('en')->setName('Page');
		$taxonomy->mergeNewTranslations();

		$this->manager->persist($taxonomy);
		$this->manager->flush();
	}

	/**
	 * Create pages
	 */
	private function createPages()
	{
		$repository = $this->manager->getRepository(Page::class);

		$user = $this->manager->getRepository(User::class)->findOneBy(['username' => 'admin']);
		$taxonomies = $this->manager->getRepository(Taxonomy::class)->findAll();

		$parent = null;
		if (($page = $repository->findOneBy(['name' => 'home'])) === null)
		{
			$page = new Page();
			$page->setName('home');
			$page->setParent($parent);
			$page->setCreatedBy($user);

			$page->translate('nl')
				->setTitle('Welkom')
				->publish()
				->setPublishedBy($user)
				->setSlug('')
				->addPagePart(
					(new HeadingPagePart())
						->setType('h1')
						->setValue('Welkom')
						->setCreatedBy($user)
				)
				->addPagePart(
					(new WYSIWYGPagePart())
						->setValue('<p>Dit is de homepagina.</p>')
						->setCreatedBy($user)
				);

			$page->translate('en')
				->setTitle('Welcome')
				->publish()
				->setPublishedBy($user)
				->setSlug('')
				->addPagePart(
					(new HeadingPagePart())
						->setType('h1')
						->setValue('Welcome')
						->setCreatedBy($user)
				)
				->addPagePart(
					(new WYSIWYGPagePart())
						->setValue('<p>This is the home page.</p>')
						->setCreatedBy($user)
				);

			$page->mergeNewTranslations();

			foreach ($taxonomies as $taxonomy)
			{
				$page->addTaxonomy($taxonomy);
			}

			$this->manager->persist($page);
			$this->manager->flush();

			$parent = $page;
		}

		if (($page = $repository->findOneBy(['name' => 'nieuws'])) === null)
		{
			$page = new Page();
			$page->setName('news');
			$page->setParent($parent);
			$page->setCreatedBy($user);

			$page->translate('nl')
				->setTitle('Nieuws')
				->publish()
				->setPublishedBy($user)
				->addPagePart(
					(new HeadingPagePart())
						->setType('h1')
						->setValue('Nieuws')
						->setCreatedBy($user)
				)
				->addPagePart(
					(new WYSIWYGPagePart())
						->setValue('<p>Dit is nieuws.</p>')
						->setCreatedBy($user)
				);

			$page->translate('en')
				->setTitle('News')
				->publish()
				->setPublishedBy($user)
				->addPagePart(
					(new HeadingPagePart())
						->setType('h1')
						->setValue('News')
						->setCreatedBy($user)
				)
				->addPagePart(
					(new WYSIWYGPagePart())
						->setValue('<p>This is news.</p>')
						->setCreatedBy($user)
				);

			$page->mergeNewTranslations();

			foreach ($taxonomies as $taxonomy)
			{
				$page->addTaxonomy($taxonomy);
			}

			$this->manager->persist($page);
			$this->manager->flush();

			$parent = $page;
		}

		if (($page = $repository->findOneBy(['name' => 'article'])) === null)
		{
			$page = new Page();
			$page->setName('article');
			$page->setParent($parent);
			$page->setCreatedBy($user);

			$page->translate('nl')
				->setTitle('Artikel')
				->publish()
				->setPublishedBy($user)
				->addPagePart(
					(new HeadingPagePart())
						->setType('h1')
						->setValue('Artikel')
						->setCreatedBy($user)
				)
				->addPagePart(
					(new WYSIWYGPagePart())
						->setValue('<p>Dit is een artikel.</p>')
						->setCreatedBy($user)
				);

			$page->translate('en')
				->setTitle('Article')
				->publish()
				->setPublishedBy($user)
				->addPagePart(
					(new HeadingPagePart())
						->setType('h1')
						->setValue('Article')
						->setCreatedBy($user)
				)
				->addPagePart(
					(new WYSIWYGPagePart())
						->setValue('<p>This is an article.</p>')
						->setCreatedBy($user)
				);

			$page->mergeNewTranslations();

			foreach ($taxonomies as $taxonomy)
			{
				$page->addTaxonomy($taxonomy);
			}

			$this->manager->persist($page);
			$this->manager->flush();
		}

//		if (($page = $repository->findOneBy(['name' => 'foobar-1'])) === null)
//		{
//			for ($i = 1; $i <= 100; $i++)
//			{
//				$page = new Page();
//				$page->setName("foobar-{$i}");
//				$page->setCreatedBy($user);
//				$page->setWeight($i);
//
//				$page->translate('nl')
//					->setTitle("Foobar {$i}")
//					->publish()
//					->setPublishedBy($user)
//					->addPagePart(
//						(new HeadingPagePart())
//							->setType('h1')
//							->setValue("Foobar {$i}")
//							->setCreatedBy($user)
//					)
//					->addPagePart(
//						(new WYSIWYGPagePart())
//							->setValue("<p>Dit is foobar {$i}.</p>")
//							->setCreatedBy($user)
//					);
//
//				$page->translate('en')
//					->setTitle("Foobar {$i}")
//					->publish()
//					->setPublishedBy($user)
//					->addPagePart(
//						(new HeadingPagePart())
//							->setType('h1')
//							->setValue("Foobar {$i}")
//							->setCreatedBy($user)
//					)
//					->addPagePart(
//						(new WYSIWYGPagePart())
//							->setValue("<p>This is foobar {$i}.</p>")
//							->setCreatedBy($user)
//					);
//
//				$page->mergeNewTranslations();
//
//				foreach ($taxonomies as $taxonomy)
//				{
//					$page->addTaxonomy($taxonomy);
//				}
//
//				$this->manager->persist($page);
//			}
//
//			$this->manager->flush();
//		}

		$this->manager->clear();
	}
}