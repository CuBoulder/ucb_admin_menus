<?php

namespace Drupal\ucb_admin_menus\Middleware;

use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Redirects an anonymous user accessing an admin/restricted page to login.
 */
class Redirect403 implements HttpKernelInterface {
  /**
   * The default HTTP kernel.
   *
   * @var \Symfony\Component\HttpKernel\HttpKernelInterface
   */
  protected $httpKernel;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $user;

  /**
   * Constructs a new Redirect403.
   *
   * @param \Symfony\Component\HttpKernel\HttpKernelInterface $http_kernel
   *   The default HTTP kernel.
   * @param \Drupal\Core\Session\AccountInterface $user
   *   The current user.
   */
  public function __construct(HttpKernelInterface $http_kernel, AccountInterface $user) {
    $this->httpKernel = $http_kernel;
    $this->user = $user;
  }

  /**
   * {@inheritdoc}
   */
  public function handle(Request $request, $type = self::MAIN_REQUEST, $catch = TRUE): Response {
    $response = $this->httpKernel->handle($request, $type, $catch);
    if ($response->getStatusCode() === Response::HTTP_FORBIDDEN && $this->user->isAnonymous()) {
      return new RedirectResponse(Url::fromRoute('user.login')->toString(), Response::HTTP_FOUND);
    }
    return $response;
  }

}
