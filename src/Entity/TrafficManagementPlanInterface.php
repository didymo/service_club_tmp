<?php

namespace Drupal\service_club_tmp\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Traffic management plan entities.
 *
 * @ingroup service_club_tmp
 */
interface TrafficManagementPlanInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Traffic management plan name.
   *
   * @return string
   *   Name of the Traffic management plan.
   */
  public function getName();

  /**
   * Sets the Traffic management plan name.
   *
   * @param string $name
   *   The Traffic management plan name.
   *
   * @return \Drupal\service_club_tmp\Entity\TrafficManagementPlanInterface
   *   The called Traffic management plan entity.
   */
  public function setName($name);

  /**
   * Gets the Traffic management plan creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Traffic management plan.
   */
  public function getCreatedTime();

  /**
   * Sets the Traffic management plan creation timestamp.
   *
   * @param int $timestamp
   *   The Traffic management plan creation timestamp.
   *
   * @return \Drupal\service_club_tmp\Entity\TrafficManagementPlanInterface
   *   The called Traffic management plan entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Traffic management plan published status indicator.
   *
   * Unpublished Traffic management plan are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Traffic management plan is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Traffic management plan.
   *
   * @param bool $published
   *   TRUE to set this Traffic management plan to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\service_club_tmp\Entity\TrafficManagementPlanInterface
   *   The called Traffic management plan entity.
   */
  public function setPublished($published);

  /**
   * Gets the Traffic management plan revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Traffic management plan revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\service_club_tmp\Entity\TrafficManagementPlanInterface
   *   The called Traffic management plan entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Traffic management plan revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Traffic management plan revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\service_club_tmp\Entity\TrafficManagementPlanInterface
   *   The called Traffic management plan entity.
   */
  public function setRevisionUserId($uid);

}
