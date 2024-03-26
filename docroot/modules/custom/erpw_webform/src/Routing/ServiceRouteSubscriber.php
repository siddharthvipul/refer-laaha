<?php

namespace Drupal\erpw_webform\Routing;

use Drupal\Core\Routing\RouteProviderInterface;
use Drupal\Core\Routing\RouteSubscriberBase;
use Drupal\Core\Url;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class ServiceRouteSubscriber extends RouteSubscriberBase {

  /**
   * The route provider.
   *
   * @var \Drupal\Core\Routing\RouteProviderInterface
   */
  protected $routeProvider;

  /**
   * Constructs a new ServiceRouteSubscriber object.
   *
   * @param \Drupal\Core\Routing\RouteProviderInterface $route_provider
   *   The route provider.
   */
  public function __construct(RouteProviderInterface $route_provider) {
    $this->routeProvider = $route_provider;
  }

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {

    // Duplicate Webform.
    if ($route = $collection->get('entity.webform.duplicate_form')) {
      $route->setRequirement('_custom_access', '\Drupal\erpw_webform\Access\ServiceFormAccessCheck::access');
    }

    // Manage Services view page.
    $manage_service_alias = '/manage-services';
    $manage_service_route = Url::fromUserInput($manage_service_alias)->getRouteName();
    if ($manage_service_route = $collection->get($manage_service_route)) {
      $manage_service_route->setRequirement('_custom_access', '\Drupal\erpw_webform\Access\ManageServiceAccessCheck::access');
    }
  }

}
