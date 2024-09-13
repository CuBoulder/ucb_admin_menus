<?php

namespace Drupal\ucb_admin_menus\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Routing\TrustedRedirectResponse;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Session\AnonymousUserSession;
use Drupal\Core\Session\SessionManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a controller for the Drupal logout route.
 */
class LogoutController extends ControllerBase {

  /**
   * The session manager.
   *
   * @var \Drupal\Core\Session\SessionManagerInterface
   */
  protected $session;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $user;

  /**
   * The logger.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * Constructs a new LogoutController.
   *
   * @param \Drupal\Core\Session\SessionManagerInterface $session
   *   The session manager.
   * @param \Drupal\Core\Session\AccountProxyInterface $user
   *   The current user.
   * @param \Psr\Log\LoggerInterface $logger
   *   The logger.
   */
  public function __construct(SessionManagerInterface $session, AccountProxyInterface $user, LoggerInterface $logger) {
    $this->session = $session;
    $this->user = $user;
    $this->logger = $logger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('session_manager'),
      $container->get('current_user'),
      $container->get('logger.factory')->get('ucb_admin_menus')
    );
  }

  /**
   * Redirects the Drupal logout route to the site homepage.
   *
   * The user's current session is destroyed and a redirect to the site
   * homepage is returned. All module `user_logout` hooks are called with one
   * notable exception: simpleSAMLphp Authentication. This module was
   * redirecting the user to a FedAuth error page instead of actually logging
   * them out. See
   * [issue #29](https://github.com/CuBoulder/ucb_admin_menus/issues/29).
   *
   * @return \Drupal\Core\Routing\TrustedRedirectResponse
   *   A redirect response.
   */
  public function logoutRedirect() {
    $user = $this->user;
    if ($user->isAuthenticated()) {
      // @see core/modules/user/user.module user_logout
      $this->logger->info('Session closed for %name.', [
        '%name' => $user->getAccountName(),
      ]);
      $this->moduleHandler()->invokeAllWith('user_logout', function (callable $hook, string $module) use ($user) {
        // Stops simpleSAMLphp Authentication from redirecting logouts to a
        // FedAuth error page.
        if ($module != 'simplesamlphp_auth') {
          call_user_func($hook, $user);
        }
      });
      $this->session->destroy();
      $user->setAccount(new AnonymousUserSession());
    }
    return new TrustedRedirectResponse(base_path());
  }

}
