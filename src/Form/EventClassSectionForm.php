<?php

namespace Drupal\service_club_tmp\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class EventClassSectionForm.
 */
class EventClassSectionForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $event_class_section = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $event_class_section->label(),
      '#description' => $this->t("Label for the Event class section."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $event_class_section->id(),
      '#machine_name' => [
        'exists' => '\Drupal\service_club_tmp\Entity\EventClassSection::load',
      ],
      '#disabled' => !$event_class_section->isNew(),
    ];

    $form['heading'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Heading'),
      '#default_value' => $event_class_section->getHeading(),
      '#description' => $this->t("The heading for this section of the Event Class text."),
      '#required' => TRUE,
    ];

    $form['description'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
      '#default_value' => $event_class_section->getDescription(),
      '#description' => $this->t("The description for this section of the Event Class text"),
      '#required' => TRUE,
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $event_class_section = $this->entity;
    $status = $event_class_section->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Event class section.', [
          '%label' => $event_class_section->label(),
        ]));
        $event_class = $this->getRouteMatch()->getParameter('event_class');
        $event_class->addSection($event_class_section->id());
        break;

      default:
        drupal_set_message($this->t('Saved the %label Event class section.', [
          '%label' => $event_class_section->label(),
        ]));
    }
    $form_state->setRedirectUrl($event_class_section->toUrl('collection'));
  }

}
