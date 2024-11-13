<?php

namespace Drupal\ucb_admin_menus\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Routing\TrustedRedirectResponse;

/**
 * Provides a controller for the Drupal help route.
 */
class HelpController extends ControllerBase {

  /**
   * Redirects the Drupal help route to CU Boulder web support.
   *
   * @return \Drupal\Core\Routing\TrustedRedirectResponse
   *   A redirect response.
   */
  public function helpRedirect() {
    return new TrustedRedirectResponse('https://webexpress.colorado.edu/');
  }

}
