<?php

namespace Drupal\service_club_tmp;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Traffic management plan entities.
 *
 * @ingroup service_club_tmp
 */
class TrafficManagementPlanListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Traffic management plan ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\service_club_tmp\Entity\TrafficManagementPlan */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.traffic_management_plan.edit_form',
      ['traffic_management_plan' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
