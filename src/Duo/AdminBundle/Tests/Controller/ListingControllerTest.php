<?php

namespace Duo\AdminBundle\Tests\Controller;

use Duo\AdminBundle\Controller\AbstractIndexController;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class ListingControllerTest extends WebTestCase
{
	/**
	 * @var Client
	 */
	private $client;

	/**
	 * {@inheritdoc}
	 */
	public function setUp()
	{
		$this->client = static::createClient();
	}

	/**
	 * @covers AbstractIndexController::indexAction()
	 */
	public function testListingSecurity()
	{
		$this->login();

		foreach ([
			'/admin/page/',
			'/admin/modules/taxonomy/',
			'/admin/security/user/',
			'/admin/security/group/',
			'/admin/security/role/',
			'/admin/seo/redirects/',
			'/admin/seo/robots/'
		 ] as $url)
		{
			$crawler = $this->client->request('GET', $url);

			$this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
			$this->assertSame('Duo', $crawler->filter('body > nav > a')->text());
		}

		$this->login(['ROLE_ADMIN']);

		foreach ([
			'/admin/security/user/',
			'/admin/security/group/',
			'/admin/security/role/'
		 ] as $url)
		{
			$this->client->request('GET', $url);
			$this->assertSame(Response::HTTP_FORBIDDEN, $this->client->getResponse()->getStatusCode());
		}
	}

	/**
	 * Login
	 *
	 * @param array $roles
	 */
	private function login(array $roles = null): void
	{
		$roles = $roles ?: ['ROLE_SUPER_ADMIN'];

		$firewall = 'admin';
		$token = new UsernamePasswordToken('admin', null, $firewall, $roles);

		$session = $this->client->getContainer()->get('session');
		$session->set("_security_{$firewall}", serialize($token));
		$session->save();

		$cookie = new Cookie($session->getName(), $session->getId());
		$this->client->getCookieJar()->set($cookie);
	}
}