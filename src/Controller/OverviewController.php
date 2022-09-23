<?php

namespace Drupal\ucb_admin_menus\Controller;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Menu\MenuActiveTrailInterface;
use Drupal\Core\Menu\MenuLinkTreeInterface;
use Drupal\Core\Menu\MenuTreeParameters;
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
	 * The menu link tree service.
	 *
	 * @var \Drupal\Core\Menu\MenuLinkTreeInterface
	 */
	protected $menuLinkTree;

	/**
	 * The active menu trail service.
	 *
	 * @var \Drupal\Core\Menu\MenuActiveTrailInterface
	 */
	protected $menuActiveTrail;

	/**
	 * Constructs a new OverviewController.
	 *
	 * @param \Drupal\system\SystemManager $systemManager
	 *   System manager service.
	 * @param \Drupal\Core\Menu\MenuLinkTreeInterface $menuLinkTree
	 *   The menu link tree service.
	 * @param \Drupal\Core\Menu\MenuActiveTrailInterface $menuActiveTrail
     *   The active menu trail service.
	 */
	public function __construct(SystemManager $systemManager, MenuLinkTreeInterface $menuLinkTree, MenuActiveTrailInterface $menuActiveTrail) {
		$this->systemManager = $systemManager;
		$this->menuLinkTree = $menuLinkTree;
		$this->menuActiveTrail = $menuActiveTrail;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function create(ContainerInterface $container) {
		return new static(
			$container->get('system.manager'),
			$container->get('menu.link_tree'),
			$container->get('menu.active_trail')
		);
	}

	/**
	 * Provides a single menu overview page.
	 * Uses code from Drupal\system\Controller\SystemOverviewPage::getAdminBlock
	 * 
	 * @return array
	 *   A render array suitable for
	 *   \Drupal\Core\Render\RendererInterface::render().
	 */
	public function singleMenuOverview($link_id) {
		$link = $this->menuActiveTrail->getActiveLink('admin');
		// Only find the children of this link.
		$parameters = new MenuTreeParameters();
		$parameters->setRoot($link_id)->excludeRoot()->setTopLevelOnly()->onlyEnabledLinks();
		$tree = $this->menuLinkTree->load(NULL, $parameters);
		$manipulators = [
			['callable' => 'menu.default_tree_manipulators:checkAccess'],
			['callable' => 'menu.default_tree_manipulators:generateIndexAndSort'],
		];
		$tree = $this->menuLinkTree->transform($tree, $manipulators);
		foreach ($tree as $key => $element) {
			// Only render accessible links.
			if (!$element->access->isAllowed()) {
				// @todo Bubble cacheability metadata of both accessible and
				//   inaccessible links. Currently made impossible by the way admin
				//   blocks are rendered.
				continue;
			}
		
			/** @var \Drupal\Core\Menu\MenuLinkInterface $link */
			$link = $element->link;
			$content[$key]['title'] = $link->getTitle();
			$content[$key]['options'] = $link->getOptions();
			$content[$key]['description'] = $link->getDescription();
			$content[$key]['url'] = $link->getUrlObject();
		}
		ksort($content);
		if($content)
			return [
				'#theme' => 'admin_block_content',
				'#content' => $content,
			];
		else return [
			'#markup' => t('There are no content types to add.'),
		];
	}

	/**
	 * Provides the add content overview page.
	 * Uses code from Drupal\system\Controller\SystemOverviewPage::overview
	 *
	 * @param string $link_id
	 *   The ID of the administrative path link for which to display child links.
	 *
	 * @return array
	 *   A render array suitable for
	 *   \Drupal\Core\Render\RendererInterface::render().
	 */
	public function multiMenuOverview($link_id) {
		$parameters = new MenuTreeParameters();
		$parameters->setRoot($link_id)->excludeRoot()->setTopLevelOnly()->onlyEnabledLinks();
		$tree = $this->menuLinkTree->load(NULL, $parameters);
		$manipulators = [
			['callable' => 'menu.default_tree_manipulators:checkAccess'],
			['callable' => 'menu.default_tree_manipulators:generateIndexAndSort'],
		];
		$tree = $this->menuLinkTree->transform($tree, $manipulators);
		$tree_access_cacheability = new CacheableMetadata();
		$blocks = [];
		foreach ($tree as $key => $element) {
			$tree_access_cacheability = $tree_access_cacheability->merge(CacheableMetadata::createFromObject($element->access));
		
			// Only render accessible links.
			if (!$element->access->isAllowed()) {
				continue;
			}
		
			$link = $element->link;
			$block['title'] = $link->getTitle();
			$block['description'] = $link->getDescription();
			$block['content'] = [
				'#theme' => 'admin_block_content',
				'#content' => $this->systemManager->getAdminBlock($link),
			];
		
			if (!empty($block['content']['#content'])) {
				$blocks[$key] = $block;
			}
		}
	
		if ($blocks) {
			ksort($blocks);
			$build = [
				'#theme' => 'admin_page',
				'#blocks' => $blocks,
			];
			$tree_access_cacheability->applyTo($build);
			return $build;
		} else {
			$build = [
				'#markup' => $this->t('There are no content types to add.'),
			];
			$tree_access_cacheability->applyTo($build);
			return $build;
		}	
	}
}
