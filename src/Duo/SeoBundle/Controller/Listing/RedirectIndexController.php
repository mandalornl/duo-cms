<?php

namespace Duo\SeoBundle\Controller\Listing;

use Doctrine\ORM\QueryBuilder;
use Duo\AdminBundle\Configuration\Action\ListAction;
use Duo\AdminBundle\Configuration\Field\Field;
use Duo\AdminBundle\Configuration\Filter\BooleanFilter;
use Duo\AdminBundle\Configuration\Filter\DateTimeFilter;
use Duo\AdminBundle\Configuration\Filter\StringFilter;
use Duo\AdminBundle\Controller\Listing\AbstractIndexController;
use Duo\SeoBundle\Entity\Redirect;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/seo/redirect", name="duo_seo_listing_redirect_")
 */
class RedirectIndexController extends AbstractIndexController
{
	use RedirectConfigurationTrait;

	/**
	 * {@inheritdoc}
	 */
	protected function defineFields(): void
	{
		$this
			->addField(new Field('active', 'duo.seo.listing.field.active'))
			->addField(new Field('permanent', 'duo.seo.listing.field.permanent'))
			->addField(new Field('origin', 'duo.seo.listing.field.origin'))
			->addField(new Field('target', 'duo.seo.listing.field.target'))
			->addField(new Field('createdAt', 'duo.seo.listing.field.created_at'))
			->addField(new Field('modifiedAt', 'duo.seo.listing.field.modified_at'));
	}

	/**
	 * {@inheritdoc}
	 */
	protected function defineFilters(): void
	{
		$this
			->addFilter(new BooleanFilter('active', 'duo.seo.listing.filter.active'))
			->addFilter(new BooleanFilter('permanent', 'duo.seo.listing.filter.permanent'))
			->addFilter(new StringFilter('origin', 'duo.seo.listing.filter.origin'))
			->addFilter(new StringFilter('target', 'duo.seo.listing.filter.target'))
			->addFilter(new DateTimeFilter('createdAt', 'duo.seo.listing.filter.created'))
			->addFilter(new DateTimeFilter('modifiedAt', 'duo.seo.listing.filter.modified'));
	}

	/**
	 * {@inheritdoc}
	 *
	 * @throws \Throwable
	 */
	protected function defineListActions(): void
	{
		$this
			->addListAction(new ListAction('duo.seo.listing.action.activate', "{$this->getRoutePrefix()}_activate"))
			->addListAction(new ListAction('duo.seo.listing.action.deactivate', "{$this->getRoutePrefix()}_deactivate"));
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/", name="index", methods={ "GET" })
	 */
	public function indexAction(Request $request): Response
	{
		return $this->doIndexAction($request);
	}

	/**
	 * Activate action
	 *
	 * @Route("/activate", name="activate", methods={ "GET", "POST" })
	 *
	 * @param Request $request
	 *
	 * @return Response
	 *
	 * @throws \Throwable
	 */
	public function activateAction(Request $request): Response
	{
		return $this->handleActivationRequest(function(Redirect $entity)
		{
			$entity->setActive(true);
		}, 'duo.seo.activate_success', $request);
	}

	/**
	 * Deactivate action
	 *
	 * @Route("/deactivate", name="deactivate", methods={ "GET", "POST" })
	 *
	 * @param Request $request
	 *
	 * @return Response
	 *
	 * @throws \Throwable
	 */
	public function deactivateAction(Request $request): Response
	{
		return $this->handleActivationRequest(function(Redirect $entity)
		{
			$entity->setActive(false);
		}, 'duo.seo.deactivate_success', $request);
	}

	/**
	 * Handle activation request
	 *
	 * @param \Closure $callback
	 * @param string $message
	 * @param Request $request
	 * @return Response
	 *
	 * @throws \Throwable
	 */
	private function handleActivationRequest(\Closure $callback, string $message, Request $request): Response
	{
		$selection = $request->get('ids', []);

		if (!count($selection))
		{
			// reply with json response
			if ($request->getRequestFormat() === 'json')
			{
				return $this->json([
					'success' => false,
					'message' => $this->get('translator')->trans('duo.admin.no_items', [], 'flashes')
				]);
			}

			$this->addFlash('warning', $this->get('translator')->trans('duo.admin.no_items', [], 'flashes'));
		}
		else
		{
			$manager = $this->getDoctrine()->getManager();

			foreach (array_chunk($selection, 100) as $ids)
			{
				$entities = $this->getDoctrine()->getRepository($this->getEntityClass())->findBy([
					'id' => $ids
				]);

				foreach ($entities as $entity)
				{
					call_user_func_array($callback, [ $entity ]);

					$manager->persist($entity);
				}

				$manager->flush();
				$manager->clear();
			}

			$manager->flush();

			// reply with json response
			if ($request->getRequestFormat() === 'json')
			{
				return $this->json([
					'success' => true,
					'message' => $this->get('translator')->trans($message, [], 'flashes')
				]);
			}

			$this->addFlash('success', $this->get('translator')->trans($message, [], 'flashes'));
		}

		return $this->redirectToRoute("{$this->getRoutePrefix()}_index");
	}

	/**
	 * {@inheritdoc}
	 */
	protected function defaultSorting(Request $request, QueryBuilder $builder): void
	{
		$builder->orderBy('e.origin', 'ASC');
	}
}