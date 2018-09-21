<?php

namespace Drupal\service_club_tmp\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining Event class entities.
 */
interface EventClassInterface extends ConfigEntityInterface {

  /**
   * Returns the Id of the Event class.
   *
   * @return string
   *   The Id of the Event class.
   */
  public function getId();

  /**
   * Returns the Event class name.
   *
   * @return string
   *   The Event class name.
   */
  public function getLabel();

  /**
   * Weight of this event class in the hierarchy of event classes.
   *
   * Each event class dominates the classes beneath it to form the hierarchy.
   *
   * @return int
   *   Weight of this event class in the hierarchy of event classes.
   */
  public function getWeight();

  /**
   * Returns the Ids of the event_class_sections that this event class has.
   *
   * @return string[]
   *   The Ids of the event_class_sections that this event class contains.
   */
  public function getEventClassSections();

  /**
   * Adds the section to the Event Class Sections array.
   */
  public function addSection($sectionId);

  /**
   * Removes the section to the Event Class Sections array.
   */
  public function deleteSection($sectionId);

}
