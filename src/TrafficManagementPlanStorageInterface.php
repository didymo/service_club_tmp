<?php

namespace Drupal\service_club_tmp;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface TrafficManagementPlanStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Traffic management plan revision IDs for a specific Traffic management plan.
   *
   * @param \Drupal\service_club_tmp\Entity\TrafficManagementPlanInterface $entity
   *   The Traffic management plan entity.
   *
   * @return int[]
   *   Traffic management plan revision IDs (in ascending order).
   */
  public function revisionIds(TrafficManagementPlanInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Traffic management plan author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Traffic management plan revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\service_club_tmp\Entity\TrafficManagementPlanInterface $entity
   *   The Traffic management plan entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(TrafficManagementPlanInterface $entity);

  /**
   * Unsets the language for all Traffic management plan with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
