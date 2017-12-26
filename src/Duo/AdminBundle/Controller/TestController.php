<?php

namespace Duo\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Duo\AdminBundle\Entity\Page;
use Duo\AdminBundle\Entity\Taxonomy;
use Duo\AdminBundle\Entity\User;
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

		if (($user = $em->getRepository(User::class)->findOneBy(['username' => 'johndoe@duo.nl'])) === null)
		{
			$user = (new User())
				->setName('John Doe')
				->setUsername('johndoe@duo.nl');

			$em->persist($user);
			$em->flush();
		}

		if (!count($taxonomies = $em->getRepository(Taxonomy::class)->findAll()))
		{
			$taxonomy = new Taxonomy();
			$taxonomy->translate('nl')->setName('Pagina');
			$taxonomy->translate('en')->setName('Page');
			$taxonomy->mergeNewTranslations();

			$em->persist($taxonomy);
			$em->flush();

			$taxonomies[] = $taxonomy;
		}

		$parent = null;
		if (($page = $em->getRepository(Page::class)->findOneBy(['name' => 'home'])) === null)
		{
			$page = new Page();
			$page->setName('home');
			$page->setParent($parent);
			$page
				->setPublished(true)
				->setCreatedBy($user);

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
			$page
				->setPublished(true)
				->setCreatedBy($user);

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
			$page
				->setPublished(true)
				->setCreatedBy($user);

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
}