<?php

namespace Drupal\service_club_tmp\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\service_club_tmp\Entity\EventClassInterface;
use Drupal\service_club_tmp\Entity\EventClassSectionInterface;

/**
 * Provides route responses for event class entities.
 */
class EventClassController extends ControllerBase {

  /**
   * Returns a form to add a new section to an event class.
   *
   * @param Drupal\service_club_tmp\Entity\EventClassInterface $event_class
   *   The Event Class this section will be added to.
   *
   * @return array
   *   The event class section add form.
   */
  public function addForm(EventClassInterface $event_class) {
    $section = $this->entityManager()->getStorage('event_class_section')->create(['eid' => $event_class->id()]);
    return $this->entityFormBuilder()->getForm($section);
  }

  /**
   * Route title callback.
   *
   * @param Drupal\service_club_tmp\Entity\EventClassInterface $event_class
   *   The Event Class.
   *
   * @return string
   *   The Event Class label as a render array.
   */
  public function eventClassTitle(EventClassInterface $event_class) {
    return ['#markup' => $event_class->label(), '#allowed_tags' => Xss::getHtmlTagList()];
  }

  /**
   * Route title callback.
   *
   * @param Drupal\service_club_tmp\Entity\EventClassSectionInterface $event_class_section
   *   The event shift.
   *
   * @return array
   *   The event class section label as a render array.
   */
  public function sectionTitle(EventClassSectionInterface $event_class_section) {
    return ['#markup' => $event_class_section->getName(), '#allowed_tags' => Xss::getHtmlTagList()];
  }

}
