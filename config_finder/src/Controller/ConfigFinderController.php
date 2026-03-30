<?php

declare(strict_types=1);

namespace Drupal\config_finder\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Extension\ModuleExtensionList;
use Drupal\Core\Routing\RouteProviderInterface;
use Drupal\Core\Access\AccessManagerInterface;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

/**
 * Provides a shortcut list of module configuration pages.
 */
final class ConfigFinderController extends ControllerBase {

  protected ModuleExtensionList $moduleExtensionList;
  protected RouteProviderInterface $routeProvider;
  protected AccessManagerInterface $accessManager;

  public function __construct(
    ModuleExtensionList $moduleExtensionList,
    RouteProviderInterface $routeProvider,
    AccessManagerInterface $accessManager,
  ) {
    $this->moduleExtensionList = $moduleExtensionList;
    $this->routeProvider = $routeProvider;
    $this->accessManager = $accessManager;
  }

  public static function create(ContainerInterface $container): self {
    return new self(
      $container->get('extension.list.module'),
      $container->get('router.route_provider'),
      $container->get('access_manager'),
    );
  }

  /**
   * Returns the shortcut page.
   */
  public function index(): array {
    $modules = $this->moduleExtensionList->getList();

    $config = $this->config('config_finder.settings');
    $accessible_only = $config->get('accessible_only') ?? TRUE;

    $rows = [];

    foreach ($modules as $machine_name => $extension) {
      $info = $extension->info ?? [];
      $route_name = $info['configure'] ?? NULL;

      if (!$route_name) {
        continue;
      }

      $module_label = $info['name'] ?? $machine_name;

      try {
        // Verify route exists.
        $this->routeProvider->getRouteByName($route_name);

        $access = $this->accessManager->checkNamedRoute(
          $route_name,
          [],
          $this->currentUser(),
          TRUE
        );

        if ($access->isAllowed()) {
          $link = Link::fromTextAndUrl(
            $module_label,
            Url::fromRoute($route_name)
          )->toRenderable();

          $status = 'Accessible';
        }
        else {
          if ($accessible_only) {
            continue;
          }

          $link = ['#markup' => $module_label];
          $status = 'Access denied';
        }
      }
      catch (RouteNotFoundException $e) {
        if ($accessible_only) {
          continue;
        }

        $link = ['#markup' => $module_label];
        $status = 'Missing route';
      }

      $rows[] = [
        'label' => $module_label,
        'data' => [
          ['data' => $link],
          ['data' => ['#markup' => $status]],
        ],
      ];
    }

    // Sort alphabetically.
    usort($rows, static function ($a, $b) {
      return strcasecmp($a['label'], $b['label']);
    });

    // Strip helper key.
    $rows = array_map(fn($row) => $row['data'], $rows);

    return [
      'table' => [
        '#type' => 'table',
        '#header' => [
          'Module',
          'Status',
        ],
        '#rows' => $rows,
        '#empty' => 'No configuration pages found.',
      ],
      '#cache' => [
        'contexts' => ['user.permissions'],
      ],
    ];
  }

}
