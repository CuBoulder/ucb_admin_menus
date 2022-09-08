<?php

namespace Drupal\ucb_custom_menus\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\system\SystemManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class OverviewController extends ControllerBase {

	/**
	 * System manager service.
	 *
	 * @var \Drupal\system\SystemManager
	 */
	protected $systemManager;

	/**
	 * Constructs a new OverviewController.
	 *
	 * @param \Drupal\system\SystemManager $systemManager
	 *   System manager service.
	 */
	public function __construct(SystemManager $systemManager) {
		$this->systemManager = $systemManager;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function create(ContainerInterface $container) {
		return new static(
			$container->get('system.manager')
		);
	}

	public function singleMenuOverview() {
		return $this->systemManager->getBlockContents();
	}
}
