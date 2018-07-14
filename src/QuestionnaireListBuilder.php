<?php

namespace Drupal\service_club_tmp;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Questionnaire entities.
 *
 * @ingroup service_club_tmp
 */
class QuestionnaireListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Questionnaire ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\service_club_tmp\Entity\Questionnaire */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.questionnaire.edit_form',
      ['questionnaire' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
