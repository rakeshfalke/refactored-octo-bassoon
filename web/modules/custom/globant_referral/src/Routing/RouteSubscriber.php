<?php
namespace Drupal\globant_referral\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    // Always deny access to '/user/register'.
/*    if ($route = $collection->get('user.register')) {
      $route->setRequirement('_access', 'FALSE');
    }
    // Always deny access to '/user/password'.
    if ($route = $collection->get('user.pass')) {
      $route->setRequirement('_access', 'FALSE');
    }
    // Always deny access to '/user/{user}/edit'.
    if ($route = $collection->get('entity.user.edit_form')) {
      $route->setRequirement('_access', 'FALSE');
    }
    // Always deny access to '/user/{user}'.
    if ($route = $collection->get('user.page')) {
      $route->setRequirement('_access', 'FALSE');
    }*/
  }
}
