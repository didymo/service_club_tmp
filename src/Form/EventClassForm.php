<?php

namespace Drupal\service_club_tmp\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class EventClassForm.
 */
class EventClassForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $event_class = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#maxlength' => 255,
      '#default_value' => $event_class->label(),
      '#description' => $this->t("Name of the Event Class."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $event_class->id(),
      '#machine_name' => [
        'exists' => '\Drupal\service_club_tmp\Entity\EventClass::load',
      ],
      '#disabled' => !$event_class->isNew(),
    ];

    $form['description'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
      '#default_value' => $event_class->getDescription(),
      '#description' => $this->t("Details what an event class entails."),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $event_class = $this->entity;
    $status = $event_class->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Event class.', [
          '%label' => $event_class->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Event class.', [
          '%label' => $event_class->label(),
        ]));
    }
    $form_state->setRedirectUrl($event_class->toUrl('collection'));
  }

}
