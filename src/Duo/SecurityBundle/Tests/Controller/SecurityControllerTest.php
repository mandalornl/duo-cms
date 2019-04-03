<?php

namespace Duo\SecurityBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class SecurityControllerTest extends WebTestCase
{
	/**
	 * @var Client
	 */
	private $client;

	/**
	 * {@inheritDoc}
	 */
	public function setUp()
	{
		$this->client = static::createClient();
	}

	/**
	 * @covers SecurityController::loginAction()
	 */
	public function testLogin()
	{
		$crawler = $this->client->request('GET', '/admin/security/user/');

		$this->assertEquals(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
		$this->assertStringStartsWith('Redirecting to', trim($crawler->filter('body')->text()));
		$this->assertContains('/admin/login', $crawler->filter('body > a')->text());

		$this->login();
		$crawler = $this->client->request('GET', '/admin/security/user/');

		$this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
		$this->assertSame('Duo', $crawler->filter('body > nav > a')->text());
	}

	/**
	 * Login
	 */
	private function login()
	{
		$firewall = 'admin';
		$token = new UsernamePasswordToken('admin', null, $firewall, ['ROLE_SUPER_ADMIN']);

		$session = $this->client->getContainer()->get('session');
		$session->set("_security_{$firewall}", serialize($token));
		$session->save();

		$cookie = new Cookie($session->getName(), $session->getId());
		$this->client->getCookieJar()->set($cookie);
	}
}
