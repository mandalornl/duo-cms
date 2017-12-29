<?php

namespace Duo\AdminBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Duo\AdminBundle\Entity\Page;
use Duo\AdminBundle\Entity\Taxonomy;
use Duo\SecurityBundle\Entity\Group;
use Duo\SecurityBundle\Entity\Role;
use Duo\SecurityBundle\Entity\User;

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
			'Anonymous' => 'IS_AUTHENTICATED_ANONYMOUSLY'
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
				'ROLE_USER'
			],
			'Administrators' => [
				'ROLE_ADMIN',
				'ROLE_USER'
			],
			'Users' => [
				'ROLE_USER'
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
			->setName('John Doe')
			->setEmail('johndoe@duo.nl')
			->setUsername('admin')
			->setPlainPassword('admin')
			->setActive(true);
		$user->setCreatedBy($user);
		$user->addGroup($group);

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
			$page->publish();
			$page->setPublishedBy($user);
			$page->setCreatedBy($user);

			$page->translate('nl')
				->setTitle('Home')
				->setContent('<p>Dit is de homepagina.</p>');

			$page->translate('en')
				->setTitle('Home')
				->setContent('<p>This is the home page.</p>');

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
			$page->publish();
			$page->setPublishedBy($user);
			$page->setCreatedBy($user);

			$page->translate('nl')
				->setTitle('Nieuws')
				->setContent('<p>Dit is nieuws.</p>');

			$page->translate('en')
				->setTitle('News')
				->setContent('<p>This is news.</p>');

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
			$page->publish();
			$page->setPublishedBy($user);
			$page->setCreatedBy($user);

			$page->translate('nl')
				->setTitle('Artikel')
				->setContent('<p>Dit is een artikel.</p>');

			$page->translate('en')
				->setTitle('Article')
				->setContent('<p>This is an article.</p>');

			$page->mergeNewTranslations();

			foreach ($taxonomies as $taxonomy)
			{
				$page->addTaxonomy($taxonomy);
			}

			$this->manager->persist($page);
			$this->manager->flush();
		}
	}
}