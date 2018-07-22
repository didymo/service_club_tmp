<?php

namespace Drupal\service_club_tmp;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Question response entities.
 *
 * @ingroup service_club_tmp
 */
class QuestionResponseListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Question response ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\service_club_tmp\Entity\QuestionResponse */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.question_response.edit_form',
      ['question_response' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
