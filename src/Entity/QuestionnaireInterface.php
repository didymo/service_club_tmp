<?php

namespace Drupal\service_club_tmp\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Questionnaire entities.
 *
 * @ingroup service_club_tmp
 */
interface QuestionnaireInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Questionnaire name.
   *
   * @return string
   *   Name of the Questionnaire.
   */
  public function getName();

  /**
   * Sets the Questionnaire name.
   *
   * @param string $name
   *   The Questionnaire name.
   *
   * @return \Drupal\service_club_tmp\Entity\QuestionnaireInterface
   *   The called Questionnaire entity.
   */
  public function setName($name);

  /**
   * Gets the Questionnaire creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Questionnaire.
   */
  public function getCreatedTime();

  /**
   * Sets the Questionnaire creation timestamp.
   *
   * @param int $timestamp
   *   The Questionnaire creation timestamp.
   *
   * @return \Drupal\service_club_tmp\Entity\QuestionnaireInterface
   *   The called Questionnaire entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Questionnaire published status indicator.
   *
   * Unpublished Questionnaire are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Questionnaire is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Questionnaire.
   *
   * @param bool $published
   *   TRUE to set this Questionnaire to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\service_club_tmp\Entity\QuestionnaireInterface
   *   The called Questionnaire entity.
   */
  public function setPublished($published);

  /**
   * Gets the Questionnaire revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Questionnaire revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\service_club_tmp\Entity\QuestionnaireInterface
   *   The called Questionnaire entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Questionnaire revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Questionnaire revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\service_club_tmp\Entity\QuestionnaireInterface
   *   The called Questionnaire entity.
   */
  public function setRevisionUserId($uid);

}
