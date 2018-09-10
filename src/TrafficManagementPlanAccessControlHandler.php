<?php

namespace Drupal\service_club_tmp;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Traffic management plan entity.
 *
 * @see \Drupal\service_club_tmp\Entity\TrafficManagementPlan.
 */
class TrafficManagementPlanAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\service_club_tmp\Entity\TrafficManagementPlanInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished traffic management plan entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published traffic management plan entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit traffic management plan entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete traffic management plan entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add traffic management plan entities');
  }

}
