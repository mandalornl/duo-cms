<?php

namespace Duo\PageBundle\Controller\Listing;

use AppBundle\Entity\PageDraft;
use Duo\DraftBundle\Controller\Listing\AbstractDraftController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/page", name="duo_page_listing_page_")
 */
class PageDraftController extends AbstractDraftController
{
	use PageConfigurationTrait;

	/**
	 * {@inheritdoc}
	 */
	protected function getDraftEntityClass(): string
	{
		return PageDraft::class;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route(
	 *     path="/draft/view/{id}",
	 *     name="draft_view",
	 *     requirements={ "id" = "\d+" },
	 *     methods={ "GET" }
	 * )
	 */
	public function viewAction(Request $request, int $id): Response
	{
		return $this->doViewAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route(
	 *     path="/{id}/draft/create.{_format}",
	 *     name="draft_create",
	 *     requirements={ "id" = "\d+", "_format" = "html|json" },
	 *     defaults={ "_format" = "html" },
	 *     methods={ "GET", "POST" }
	 * )
	 */
	public function createAction(Request $request, int $id): Response
	{
		return $this->doCreateAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route(
	 *     path="/draft/apply/{id}.{_format}",
	 *     name="draft_apply",
	 *     requirements={ "id" = "\d+", "_format" = "html|json" },
	 *     defaults={ "_format" = "html" },
	 *     methods={ "GET", "POST" }
	 * )
	 */
	public function applyAction(Request $request, int $id): Response
	{
		return $this->doApplyAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route(
	 *     path="/draft/destroy/{id}.{_format}",
	 *     name="draft_destroy",
	 *     requirements={ "id" = "\d+", "_format" = "html|json" },
	 *     defaults={ "_format" = "html" },
	 *     methods={ "GET", "POST" }
	 * )
	 */
	public function destroyAction(Request $request, int $id): Response
	{
		return $this->doDestroyAction($request, $id);
	}
}