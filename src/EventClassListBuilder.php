<?php

namespace Drupal\service_club_tmp;

use Drupal\Core\Config\Entity\DraggableListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a listing of Event class entities.
 */
class EventClassListBuilder extends DraggableListBuilder {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * The entity manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The renderer service.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * Constructs a new EventClassListBuilder object.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type definition.
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity manager service.
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The renderer service.
   */
  public function __construct(EntityTypeInterface $entity_type, AccountInterface $current_user, EntityTypeManagerInterface $entity_type_manager, RendererInterface $renderer = NULL) {
    parent::__construct($entity_type, $entity_type_manager->getStorage($entity_type->id()));

    $this->currentUser = $current_user;
    $this->entityTypeManager = $entity_type_manager;
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('current_user'),
      $container->get('entity_type.manager'),
      $container->get('renderer')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Event class');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = $entity->label();
    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'event_class_admin_overview';
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaultOperations(EntityInterface $entity) {
    $operations = parent::getDefaultOperations($entity);

    if (isset($operations['edit'])) {
      $operations['edit']['title'] = t('Edit Event Class');
    }

    if ($entity->access('access event class overview')) {
      $operations['list'] = [
        'title' => t('List Sections'),
        'weight' => 0,
        'url' => $entity->toUrl('section-list'),
      ];
    }

    $event_class_section_access_control_handler = $this->entityTypeManager->getAccessControlHandler('event_class_section');
    if ($event_class_section_access_control_handler->createAccess($entity->id())) {
      $operations['add'] = [
        'title' => t('Add Section'),
        'weight' => 10,
        'url' => Url::fromRoute('entity.event_class_section.add_form', ['event_class' => $entity->id()]),
      ];
    }

    unset($operations['delete']);

    return $operations;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $form['actions']['submit']['#value'] = $this->t('Save');
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    drupal_set_message($this->t('The Event Class Hierarchy has been saved.'));
  }

}
