<?php

namespace Drupal\service_club_tmp\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\service_club_tmp\Entity\EventClass;

/**
 * Class QuestionForm.
 */
class QuestionForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $question = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Question'),
      '#maxlength' => 255,
      '#default_value' => $question->label(),
      '#description' => $this->t("The question that the user will be asked."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $question->id(),
      '#machine_name' => [
        'exists' => '\Drupal\service_club_tmp\Entity\Question::load',
      ],
      '#disabled' => !$question->isNew(),
    ];

    $form['event_class'] = [
      '#type' => 'entity_autocomplete',
      '#target_type' => 'event_class',
      '#title' => $this->t('Event Class'),
      '#default_value' => EventClass::load($question->getEventClass()),
      '#description' => $this->t("Answering yes to this question, places the event into this Event Class."),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $question = $this->entity;
    $status = $question->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the Question: %label.', [
          '%label' => $question->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the Question :%label.', [
          '%label' => $question->label(),
        ]));
    }
    $form_state->setRedirectUrl($question->toUrl('collection'));
  }

}
