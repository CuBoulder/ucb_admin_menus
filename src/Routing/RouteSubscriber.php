<?php

namespace Drupal\ucb_admin_menus\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events and makes overrides as necessary.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {

    // Redirects help pages to Web Support.
    if ($route = $collection->get('help.main')) {
      $route->setDefault('_controller', 'Drupal\ucb_admin_menus\Controller\HelpController::helpRedirect');
    }
    if ($route = $collection->get('help.page')) {
      $route->setDefault('_controller', 'Drupal\ucb_admin_menus\Controller\HelpController::helpRedirect');
    }

    // Replaces the default "Add content" page with the better one with
    // categories.
    if ($route = $collection->get('node.add_page')) {
      $route->setDefault('_controller', 'Drupal\ucb_admin_menus\Controller\OverviewController::multiMenuOverview');
      $route->setDefault('link_id', 'ucb_admin_menus.node.add');
    }

    // Stops simpleSAMLphp Authentication from redirecting logouts to a FedAuth
    // error page.
    if ($route = $collection->get('user.logout')) {
      $route->setDefault('_controller', 'Drupal\ucb_admin_menus\Controller\LogoutController::logoutRedirect');
    }

  }

}
