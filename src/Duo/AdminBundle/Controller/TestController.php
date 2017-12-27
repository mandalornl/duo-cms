<?php

namespace Duo\AdminBundle\Controller;

use Duo\AdminBundle\Entity\Page;
use Duo\AdminBundle\Entity\Security\Group;
use Duo\AdminBundle\Entity\Security\Role;
use Duo\AdminBundle\Entity\Security\User;
use Duo\AdminBundle\Entity\Taxonomy;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TestController extends Controller
{
	/**
	 * @Route("/", name="homepage")
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function indexAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();

		if (($group = $em->getRepository(Group::class)->findOneBy(['name' => 'Super Administraotrs'])) === null)
		{
			$group = $this->createGroupsAndRoles();
		}

		if (($user = $em->getRepository(User::class)->findOneBy(['username' => 'johndoe@duo.nl'])) === null)
		{
			$user = $this->createUser($group);
		}

		if (!count($taxonomies = $em->getRepository(Taxonomy::class)->findAll()))
		{
			$taxonomies = $this->createTaxonomies($user);
		}

		$parent = null;
		if (($page = $em->getRepository(Page::class)->findOneBy(['name' => 'home'])) === null)
		{
			$page = new Page();
			$page->setName('home');
			$page->setParent($parent);
			$page->publish();
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

			$em->persist($page);
			$em->flush();

			$parent = $page;
		}

		if (($page = $em->getRepository(Page::class)->findOneBy(['name' => 'nieuws'])) === null)
		{
			$page = new Page();
			$page->setName('news');
			$page->setParent($parent);
			$page->publish();
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

			$em->persist($page);
			$em->flush();

			$parent = $page;
		}

		if (($page = $em->getRepository(Page::class)->findOneBy(['name' => 'article'])) === null)
		{
			$page = new Page();
			$page->setName('article');
			$page->setParent($parent);
			$page->publish();
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

			$em->persist($page);
			$em->flush();
		}

		return $this->json('Done!');
	}

	/**
	 * Create groups and roles
	 *
	 * @return Group
	 */
	private function createGroupsAndRoles(): Group
	{
		$em = $this->getDoctrine()->getManager();

		$superAdminGroup = null;

		foreach ([
			'Super Administrators' => [
				'Super Admin' => 'ROLE_SUPER_ADMIN'
			],
			'Administrators' => [
				'Admin' => 'ROLE_ADMIN'
			],
			'Users' => [
				'User' => 'ROLE_USER'
			],
			'Guests' => [
				'Anonymous' => 'IS_AUTHENTICATED_ANONYMOUSLY'
			]
		 ] as $groupName => $roles)
		{
			$group = (new Group())
				->setName($groupName);

			foreach ($roles as $name => $roleName)
			{
				$role = (new Role())
					->setName($name)
					->setRole($roleName);

				$group->addRole($role);
			}

			$em->persist($group);

			if ($group->getName() === 'Super Administrators')
			{
				$superAdminGroup = $group;
			}
		}

		$em->flush();

		return $superAdminGroup;
	}

	/**
	 * Create user
	 *
	 * @param Group $group
	 *
	 * @return User
	 */
	private function createUser(Group $group): User
	{
		$em = $this->getDoctrine()->getManager();

		$user = (new User())
			->setName('John Doe')
			->setUsername('johndoe@duo.nl')
			->setPassword('mQBzYKPs0nqNm88LzGd53dOYWHmI85pu')
			->setSalt('yP#qMnG-3(n-,gOrJw58@PZlpJ-1l~')
			->setActive(true);
		$user->setCreatedBy($user);
		$user->addGroup($group);

		$em->persist($user);
		$em->flush();

		return $user;
	}

	/**
	 * Create taxonomies
	 *
	 * @param User $user
	 *
	 * @return Taxonomy[]
	 */
	private function createTaxonomies(User $user): array
	{
		$em = $this->getDoctrine()->getManager();

		$taxonomy = (new Taxonomy());
		$taxonomy->setCreatedBy($user);
		$taxonomy->translate('nl')->setName('Pagina');
		$taxonomy->translate('en')->setName('Page');
		$taxonomy->mergeNewTranslations();

		$em->persist($taxonomy);
		$em->flush();

		return [$taxonomy];
	}
}