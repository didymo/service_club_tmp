<?php

namespace Drupal\service_club_tmp\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Questionnaire entities.
 *
 * @ingroup service_club_tmp
 */
interface QuestionnaireInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

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

}
