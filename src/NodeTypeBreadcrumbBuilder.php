<?php

namespace Drupal\ucb_custom_menus;

use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Link;
use Drupal\Core\Menu\MenuLinkManagerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

class NodeTypeBreadcrumbBuilder implements BreadcrumbBuilderInterface {
	use StringTranslationTrait;

	/**
	 * The menu link manager service.
	 *
	 * @var \Drupal\Core\Menu\MenuLinkManagerInterface
	 */
	protected $menuLinkManager;

	/**
	 * Constructs a new NodeTypeBreadcrumbBuilder.
	 * 
	 * @param \Drupal\Core\Menu\MenuLinkManagerInterface $menuLinkManager
	 *   The menu link manager service.
	 */
	public function __construct(MenuLinkManagerInterface $menuLinkManager) {
		$this->menuLinkManager = $menuLinkManager;
	}

	/**
	 * {@inheritdoc}
	 */
	public function applies(RouteMatchInterface $route_match) {
		return $route_match->getRouteName() == 'node.add';
	}

	/**
	 * {@inheritdoc}
	 */
	public function build(RouteMatchInterface $route_match) {
		// Establishes the root of the trail
		/** @var \Drupal\node\NodeTypeInterface $nodeType */
		$nodeType = $route_match->getParameter('node_type');
		$breadcrumb = new Breadcrumb();
		$breadcrumb
			->addLink(Link::createFromRoute($this->t('Home'), '<front>'))
			->addLink(Link::createFromRoute($this->t('Add content'), 'node.add_page'));

		try {
			// Adds the parent link from this module's overview pages
			/** @var \Drupal\Core\Menu\MenuLinkInterface $link */
			$link = $this->menuLinkManager->createInstance('admin_toolbar_tools.extra_links:node.add.' . $nodeType->id());
			$parentId = $link->getParent();
			if($parentId) {
				/** @var \Drupal\Core\Menu\MenuLinkInterface $parentLink */
				$parentLink = $this->menuLinkManager->createInstance($parentId);
				$breadcrumb->addLink(Link::createFromRoute($parentLink->getTitle(), $parentLink->getRouteName(), $parentLink->getRouteParameters()));
				$breadcrumb->addCacheableDependency($parentLink);
			}
		} catch(PluginException $e) {}

		// Cache stuff
		$breadcrumb->addCacheableDependency($nodeType);
		$breadcrumb->addCacheContexts(['route']);

		return $breadcrumb;
	}
}
