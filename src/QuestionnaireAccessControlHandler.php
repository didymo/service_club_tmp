<?php

namespace Drupal\service_club_tmp;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Questionnaire entity.
 *
 * @see \Drupal\service_club_tmp\Entity\Questionnaire.
 */
class QuestionnaireAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\service_club_tmp\Entity\QuestionnaireInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished questionnaire entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published questionnaire entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit questionnaire entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete questionnaire entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add questionnaire entities');
  }

}
