<?php

namespace Drupal\service_club_tmp\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining Event class section entities.
 */
interface EventClassSectionInterface extends ConfigEntityInterface {

  /**
   * Returns the section's heading.
   */
  public function getHeading();

  /**
   * Returns the section's weight.
   */
  public function getWeight();

  /**
   * Returns the section's description.
   */
  public function getDescription();
}
