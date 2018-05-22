<?php

namespace Duo\AdminBundle\Controller;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use Duo\AdminBundle\Twig\TwigContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED') and has_role('ROLE_ADMIN')")
 */
abstract class AbstractController extends Controller
{
	/**
	 * @var string
	 */
	protected $routePrefix;

	/**
	 * Set route prefix
	 *
	 * @return string
	 *
	 * @throws \Throwable
	 */
	protected function getRoutePrefix(): string
	{
		if ($this->routePrefix !== null)
		{
			return $this->routePrefix;
		}

		$annotationName = Route::class;
		$reflectionClass = new \ReflectionClass($this);

		$reader = new AnnotationReader();
		$annotation = $reader->getClassAnnotation($reflectionClass, $annotationName);

		if ($annotation === null)
		{
			$class = get_class($this);

			throw new AnnotationException("Class '{$class}' is missing annotation '{$annotationName}'");
		}

		return $this->routePrefix = rtrim($annotation->getName(), '_');
	}

	/**
	 * Entity not found
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return JsonResponse
	 */
	protected function entityNotFound(Request $request, int $id): JsonResponse
	{
		$error = "Entity '{$this->getEntityClass()}::{$id}' not found";

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
	 * @param string $fallbackUrl [optional]
	 * @param Request $request [optional]
	 *
	 * @return RedirectResponse
	 */
	protected function redirectToReferer(string $fallbackUrl, Request $request = null): RedirectResponse
	{
		if ($request === null)
		{
			$request = $this->get('request_stack')->getCurrentRequest();
		}

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
			'menu' => $this->get('duo.admin.menu_builder')->createView(),
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