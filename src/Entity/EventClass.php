<?php

namespace Drupal\service_club_tmp\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\service_club_tmp\Entity\EventClassSection;

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
 *       "delete" = "Drupal\service_club_tmp\Form\EventClassDeleteForm",
 *       "section-list" = "Drupal\service_club_tmp\Form\OverviewEventClassSections"
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
 *     "uuid" = "uuid",
 *     "event_class_sections" = "event_class_sections"
 *   },
 *   links = {
 *     "canonical" = "/admin/config/tmp/event_class/{event_class}",
 *     "add-form" = "/admin/config/tmp/event_class/add",
 *     "edit-form" = "/admin/config/tmp/event_class/{event_class}/edit",
 *     "delete-form" = "/admin/config/tmp/event_class/{event_class}/delete",
 *     "collection" = "/admin/config/tmp/event_class",
 *     "section-list" = "/admin/config/tmp/event_class/{event_class}/sections"
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
   * {@inheritdoc}
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
   * {@inheritdoc}
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
   * {@inheritdoc}
   */
  public function getWeight() {
    return $this->weight;
  }

  /**
   * The Event Class Section ids for this event class.
   *
   * @var string[]
   */
  protected $sections = array();

  /**
   * {@inheritdoc}
   */
  public function getEventClassSections() {
    $references = $this->get('sections');
    $sections = array();
    foreach ($references as $sectionId) {
      $sections[] = EventClassSection::Load($sectionId);
    }
    return $sections;
  }

  /**
   * {@inheritdoc}
   */
  public function addSection($sectionId) {
    $sections = $this->get('sections');
    array_push($sections, $sectionId);
    $this->set('sections', $sections);
    $this->save(TRUE);
  }

  /**
   * {@inheritdoc}
   */
  public function deleteSection($sectionId) {
    $sections = $this->get('sections');
    unset($sections[$sectionId]);
    $this->set('sections', $sections);
    $this->save(TRUE);
  }

  /**
   * {@inheritdoc}
   */
  public function getInformation() {
    $sections = $this->getEventClassSections();
    $section_data = array();

    foreach ($sections as $section) {
      $section_data[] = array($section->getHeading() => $section->getDescription());
    }

    $information = array(
      "Title" => $this->label,
      "Sections" => $section_data,
    );

    return $information;
  }

}
