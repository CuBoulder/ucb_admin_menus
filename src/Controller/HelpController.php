<?php

/**
 * @file
 * Contains \Drupal\ucb_admin_menus\Controller\HelpController.
 */

namespace Drupal\ucb_admin_menus\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Routing\TrustedRedirectResponse;

class HelpController extends ControllerBase {
	/**
	 * @return \Drupal\Core\Routing\TrustedRedirectResponse
	 *   A redirect to the CU Boulder web support site.
	 */
	public function helpRedirect() {
		return new TrustedRedirectResponse('https://websupport.colorado.edu/');
	}
}
