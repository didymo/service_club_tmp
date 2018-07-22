<?php

namespace Drupal\service_club_tmp\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Question response entities.
 *
 * @ingroup service_club_tmp
 */
interface QuestionResponseInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Question response name.
   *
   * @return string
   *   Name of the Question response.
   */
  public function getName();

  /**
   * Sets the Question response name.
   *
   * @param string $name
   *   The Question response name.
   *
   * @return \Drupal\service_club_tmp\Entity\QuestionResponseInterface
   *   The called Question response entity.
   */
  public function setName($name);

  /**
   * Gets the Question response creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Question response.
   */
  public function getCreatedTime();

  /**
   * Sets the Question response creation timestamp.
   *
   * @param int $timestamp
   *   The Question response creation timestamp.
   *
   * @return \Drupal\service_club_tmp\Entity\QuestionResponseInterface
   *   The called Question response entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Question response published status indicator.
   *
   * Unpublished Question response are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Question response is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Question response.
   *
   * @param bool $published
   *   TRUE to set this Question response to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\service_club_tmp\Entity\QuestionResponseInterface
   *   The called Question response entity.
   */
  public function setPublished($published);

}
