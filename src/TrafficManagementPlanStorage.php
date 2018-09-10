<?php

namespace Drupal\service_club_tmp;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\service_club_tmp\Entity\TrafficManagementPlanInterface;

/**
 * Defines the storage handler class for Traffic management plan entities.
 *
 * This extends the base storage class, adding required special handling for
 * Traffic management plan entities.
 *
 * @ingroup service_club_tmp
 */
class TrafficManagementPlanStorage extends SqlContentEntityStorage implements TrafficManagementPlanStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(TrafficManagementPlanInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {traffic_management_plan_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {traffic_management_plan_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(TrafficManagementPlanInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {traffic_management_plan_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('traffic_management_plan_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
