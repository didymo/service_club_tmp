<?php

namespace Drupal\service_club_tmp\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the Event class entity.
 *
 * @ConfigEntityType(
 *   id = "event_class",
 *   label = @Translation("Event class"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\service_club_tmp\EventClassListBuilder",
 *     "form" = {
 *       "add" = "Drupal\service_club_tmp\Form\EventClassForm",
 *       "edit" = "Drupal\service_club_tmp\Form\EventClassForm",
 *       "delete" = "Drupal\service_club_tmp\Form\EventClassDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\service_club_tmp\EventClassHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "event_class",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "weight" = "weight",
 *     "description" = "description",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/config/tmp/event_class/{event_class}",
 *     "add-form" = "/admin/config/tmp/event_class/add",
 *     "edit-form" = "/admin/config/tmp/event_class/{event_class}/edit",
 *     "delete-form" = "/admin/config/tmp/event_class/{event_class}/delete",
 *     "collection" = "/admin/config/tmp/event_class"
 *   }
 * )
 */
class EventClass extends ConfigEntityBase implements EventClassInterface {

  /**
   * The Event class ID.
   *
   * @var string
   */
  protected $id;

  /**
   * Returns the Id of the Event class.
   *
   * @return string
   *   The Id of the Event class.
   */
  public function getId() {
    return $this->id;
  }

  /**
   * The Event class name.
   *
   * @var string
   */
  protected $label;

  /**
   * Returns the Event class name.
   *
   * @return string
   *   The Event class name.
   */
  public function getLabel() {
    return $this->label;
  }

  /**
   * Weight of this event class in the hierarchy of event classes.
   *
   * Each event class dominates the classes beneath it to form the hierarchy.
   *
   * @var int
   */
  protected $weight = 0;

  /**
   * Weight of this event class in the hierarchy of event classes.
   *
   * Each event class dominates the classes beneath it to form the hierarchy.
   *
   * @return int
   *   Weight of this event class in the hierarchy of event classes.
   */
  public function getWeight() {
    return $this->weight;
  }

  /**
   * Holds the description of the event class.
   *
   * @var string
   */
  protected $description;

  /**
   * Returns the description of the event class.
   *
   * @return string
   *   The description of the event class.
   */
  public function getDescription() {
    return $this->description;
  }

}
