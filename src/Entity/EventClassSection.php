<?php

namespace Drupal\service_club_tmp\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the Event class section entity.
 *
 * @ConfigEntityType(
 *   id = "event_class_section",
 *   label = @Translation("Event class section"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\service_club_tmp\EventClassSectionListBuilder",
 *     "form" = {
 *       "default" = "Drupal\service_club_tmp\Form\EventClassSectionForm",
 *       "add" = "Drupal\service_club_tmp\Form\EventClassSectionForm",
 *       "edit" = "Drupal\service_club_tmp\Form\EventClassSectionForm",
 *       "delete" = "Drupal\service_club_tmp\Form\EventClassSectionDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\service_club_tmp\EventClassSectionHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "event_class_section",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/config/tmp/event_class_section/{event_class_section}",
 *     "add-form" = "/admin/config/tmp/event_class_section/add",
 *     "edit-form" = "/admin/config/tmp/event_class_section/{event_class_section}/edit",
 *     "delete-form" = "/admin/config/tmp/event_class_section/{event_class_section}/delete",
 *     "collection" = "/admin/config/tmp/event_class_section"
 *   }
 * )
 */
class EventClassSection extends ConfigEntityBase implements EventClassSectionInterface {

  /**
   * The Event class section ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Event class section label.
   *
   * @var string
   */
  protected $label;

  /**
   * {@inheritdoc}
   */
  public function getHeading() {
    return $this->get('heading');
  }

  /**
   * {@inheritdoc}
   */
  public function getWeight() {
    return $this->get('weight');
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->get('description');
  }

}
