<?php

namespace Drupal\service_club_tmp;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\service_club_tmp\Entity\QuestionnaireInterface;

/**
 * Defines the storage handler class for Questionnaire entities.
 *
 * This extends the base storage class, adding required special handling for
 * Questionnaire entities.
 *
 * @ingroup service_club_tmp
 */
interface QuestionnaireStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Questionnaire revision IDs for a specific Questionnaire.
   *
   * @param \Drupal\service_club_tmp\Entity\QuestionnaireInterface $entity
   *   The Questionnaire entity.
   *
   * @return int[]
   *   Questionnaire revision IDs (in ascending order).
   */
  public function revisionIds(QuestionnaireInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Questionnaire author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Questionnaire revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\service_club_tmp\Entity\QuestionnaireInterface $entity
   *   The Questionnaire entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(QuestionnaireInterface $entity);

  /**
   * Unsets the language for all Questionnaire with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
