<?php

namespace Drupal\service_club_tmp\Plugin\rest\resource;

use Drupal\Core\Session\AccountProxyInterface;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Drupal\service_club_tmp\Entity\TrafficManagementPlan;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Provides a resource to get view modes by entity and bundle.
 *
 * @RestResource(
 *   id = "create_tmp",
 *   label = @Translation("Create TMP"),
 *   uri_paths = {
 *     "canonical" = "/tmp",
 *     "https://www.drupal.org/link-relations/create" = "/tmp"
 *   }
 * )
 */
class CreateTMP extends ResourceBase {

  /**
   * A current user instance.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * Constructs a new StoreMapBounds object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param array $serializer_formats
   *   The available serialization formats.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   A current user instance.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    AccountProxyInterface $current_user) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);

    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('service_club_tmp'),
      $container->get('current_user')
    );
  }

  /**
   * Responds to POST requests.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity object.
   *
   * @return \Drupal\rest\ModifiedResourceResponse
   *   The HTTP response object.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws exception expected.
   */
  public function post($json) {

    // Use current user after pass authentication to validate access.
    if (!$this->currentUser->hasPermission('access content')) {
      throw new AccessDeniedHttpException();
    }

    // Dissect json string into an array.
    $name = $json["name"];
    $north_bound = $json["leftTop"]["latitude"];
    $east_bound = $json["rightBottom"]["longitude"];
    $south_bound = $json["rightBottom"]["latitude"];
    $west_bound = $json["leftTop"]["longitude"];

    // Check values are valid.
    $errors = array();
    if (($north_bound > 90) || ($north_bound < -90)) {
      $errors[] = array("North bound needs to be a latitude between -90 & 90" => "Given: $north_bound");
    }
    if (($east_bound > 180) || ($east_bound < -180)) {
      $errors[] = array("East bound needs to be a latitude between -180 & 180" => "Given: $east_bound");
    }
    if (($south_bound > 90) || ($south_bound < -90)) {
      $errors[] = array("South bound needs to be a latitude between -90 & 90" => "Given: $south_bound");
    }
    if (($west_bound > 180) || ($west_bound < -180)) {
      $errors[] = array("West bound needs to be a latitude between -180 & 180" => "Given: $west_bound");
    }

    if (!empty($errors)) {
      return new ModifiedResourceResponse($errors, 400);
    }

    // Create new TMP.
    $tmp = TrafficManagementPlan::create([
      'type' => 'traffic_management_plan',
      'name' => $name,
      'north_bound' => $north_bound,
      'east_bound' => $east_bound,
      'south_bound' => $south_bound,
      'west_bound' => $west_bound,
    ]);
    $tmp->save();

    return new ModifiedResourceResponse($tmp, 200);
  }

}
