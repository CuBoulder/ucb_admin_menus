<?php

namespace Drupal\ucb_admin_menus\Plugin\Menu;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Menu\MenuLinkDefault;
use Drupal\Core\Menu\StaticMenuLinkOverridesInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a base class for menu links in the "Add content" page.
 */
class NodeTypeMenuLink extends MenuLinkDefault {

  /**
   * The associated node type.
   *
   * @var \Drupal\node\NodeTypeInterface
   */
  protected $nodeType;

  /**
   * Creates a new NodeTypeMenuLink.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Menu\StaticMenuLinkOverridesInterface $static_override
   *   The static override storage.
   * @param \Drupal\Core\Entity\EntityStorageInterface $entityStorage
   *   The node type storage.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, StaticMenuLinkOverridesInterface $static_override, EntityStorageInterface $entityStorage) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $static_override);
    // Gets the node type id from the menu link id.
    $menuLinkId = $this->pluginDefinition['id'];
    $nodeTypeId = substr($menuLinkId, strripos($menuLinkId, '.node.add.') + 10);
    // Loads the node type from the node type storage (falls back to basic
    // page if it doesn't exist to avoid breaking the site).
    $this->nodeType = $entityStorage->load($nodeTypeId) ?? $entityStorage->load('basic_page');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('menu_link.static.overrides'),
      $container->get('entity_type.manager')->getStorage('node_type')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getTitle() {
    return $this->nodeType->label();
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->nodeType->getDescription();
  }

  /**
   * {@inheritdoc}
   */
  public function getRouteName() {
    return 'node.add';
  }

  /**
   * {@inheritdoc}
   */
  public function getRouteParameters() {
    return ['node_type' => $this->nodeType->id()];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    return $this->nodeType->getCacheContexts();
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    return $this->nodeType->getCacheTags();
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return $this->nodeType->getCacheMaxAge();
  }

}
