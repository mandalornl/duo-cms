<?php

namespace Duo\TranslatorBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Duo\TranslatorBundle\Entity\Entry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
 */
class ReloadController extends AbstractController
{
	/**
	 * @var KernelInterface
	 */
	private $kernel;

	/**
	 * ReloadController constructor
	 *
	 * @param KernelInterface $kernel
	 */
	public function __construct(KernelInterface $kernel)
	{
		$this->kernel = $kernel;
	}

	/**
	 * Reload action
	 *
	 * @return Response
	 */
	public function reloadAction(): Response
	{
		$application = new Application($this->kernel);
		$application->setAutoExit(false);

		$input = new ArrayInput([
			'command' => 'duo:translator:cache',
			'--clear' => null
		]);

		try
		{
			$application->run($input);

			/**
			 * @var EntityRepository $repository
			 */
			$repository = $this->getDoctrine()->getRepository(Entry::class);

			$repository
				->createQueryBuilder('e')
				->update()
				->set('e.flag', ':flag')
				->where('e.flag <> :flag')
				->setParameter('flag', Entry::FLAG_NONE)
				->getQuery()
				->execute();

			$this->addFlash('success', $this->get('translator')->trans('duo_translator.reload_success', [], 'flashes'));
		}
		catch (\Exception $e)
		{
			$this->addFlash('error', $this->get('translator')->trans('duo_translator.reload_error', [], 'flashes'));
		}

		return $this->redirectToRoute('duo_translator_listing_entry_index');
	}
}
