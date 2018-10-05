<?php

namespace Duo\AdminBundle\Controller\Listing;

use Duo\AdminBundle\Controller\RoutePrefixTrait;
use Duo\AdminBundle\Tools\Menu\MenuBuilder;
use Duo\AdminBundle\Twig\TwigContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED') and has_role('ROLE_ADMIN')")
 */
abstract class AbstractController extends Controller
{
	use RoutePrefixTrait;

	/**
	 * Entity not found
	 *
	 * @param Request $request
	 * @param int $id
	 * @param string $className [optional]
	 *
	 * @return JsonResponse
	 */
	protected function entityNotFound(Request $request, int $id, string $className = null): JsonResponse
	{
		$className = $className ?: $this->getEntityClass();

		$error = "Entity '{$className}::{$id}' not found";

		// reply with json response
		if ($request->getRequestFormat() === 'json')
		{
			return $this->json([
				'error' => $error
			]);
		}

		throw $this->createNotFoundException($error);
	}

	/**
	 * Interface not implemented
	 *
	 * @param Request $request
	 * @param int $id
	 * @param string $interfaceName
	 * @param string $className [optional]
	 *
	 * @return JsonResponse
	 */
	protected function interfaceNotImplemented(
		Request $request,
		int $id,
		string $interfaceName,
		string $className = null
	): JsonResponse
	{
		$className = $className ?: $this->getEntityClass();

		$error = "Entity '{$className}::{$id}' doesn't implement '{$interfaceName}'";

		// reply with json response
		if ($request->getRequestFormat() === 'json')
		{
			return $this->json([
				'error' => $error
			]);
		}

		throw $this->createNotFoundException($error);
	}

	/**
	 * Redirect to referer
	 *
	 * @param Request $request
	 * @param string $fallbackUrl
	 *
	 * @return RedirectResponse
	 */
	protected function redirectToReferer(Request $request, string $fallbackUrl): RedirectResponse
	{
		// use fallback route if referer is missing
		if ($request->headers->get('referer') === null)
		{
			return $this->redirect($fallbackUrl);
		}

		return $this->redirect($request->headers->get('referer'));
	}

	/**
	 * Get default context
	 *
	 * @param array $context [optional]
	 *
	 * @return TwigContext
	 *
	 * @throws \Throwable
	 */
	protected function getDefaultContext(array $context = []): TwigContext
	{
		$context = array_merge([
			'menu' => $this->get(MenuBuilder::class)->createView(),
			'routePrefix' => $this->getRoutePrefix(),
			'type' => $this->getType()
		], $context);

		return new TwigContext($context);
	}

	/**
	 * Get entity class
	 *
	 * @return string
	 */
	abstract protected function getEntityClass(): string;

	/**
	 * Get form type
	 *
	 * @return string
	 */
	abstract protected function getFormType(): ?string;

	/**
	 * Get type
	 *
	 * @return string
	 */
	abstract protected function getType(): string;
}