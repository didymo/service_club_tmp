<?php

namespace Drupal\service_club_tmp\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Questionnaire edit forms.
 *
 * @ingroup service_club_tmp
 */
class QuestionnaireForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\service_club_tmp\Entity\Questionnaire */
    $form = parent::buildForm($form, $form_state);

    $entity = $this->entity;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Questionnaire.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Questionnaire.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.questionnaire.canonical', ['questionnaire' => $entity->id()]);
  }

}
