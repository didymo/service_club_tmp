<?php

namespace Drupal\service_club_tmp\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Question response entities.
 */
class QuestionResponseViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}
