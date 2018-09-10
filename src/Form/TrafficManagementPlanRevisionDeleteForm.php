<?php

namespace Drupal\service_club_tmp\Form;

use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form for deleting a Traffic management plan revision.
 *
 * @ingroup service_club_tmp
 */
class TrafficManagementPlanRevisionDeleteForm extends ConfirmFormBase {


  /**
   * The Traffic management plan revision.
   *
   * @var \Drupal\service_club_tmp\Entity\TrafficManagementPlanInterface
   */
  protected $revision;

  /**
   * The Traffic management plan storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $TrafficManagementPlanStorage;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * Constructs a new TrafficManagementPlanRevisionDeleteForm.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $entity_storage
   *   The entity storage.
   * @param \Drupal\Core\Database\Connection $connection
   *   The database connection.
   */
  public function __construct(EntityStorageInterface $entity_storage, Connection $connection) {
    $this->TrafficManagementPlanStorage = $entity_storage;
    $this->connection = $connection;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $entity_manager = $container->get('entity.manager');
    return new static(
      $entity_manager->getStorage('traffic_management_plan'),
      $container->get('database')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'traffic_management_plan_revision_delete_confirm';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return t('Are you sure you want to delete the revision from %revision-date?', ['%revision-date' => format_date($this->revision->getRevisionCreationTime())]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.traffic_management_plan.version_history', ['traffic_management_plan' => $this->revision->id()]);
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $traffic_management_plan_revision = NULL) {
    $this->revision = $this->TrafficManagementPlanStorage->loadRevision($traffic_management_plan_revision);
    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->TrafficManagementPlanStorage->deleteRevision($this->revision->getRevisionId());

    $this->logger('content')->notice('Traffic management plan: deleted %title revision %revision.', ['%title' => $this->revision->label(), '%revision' => $this->revision->getRevisionId()]);
    drupal_set_message(t('Revision from %revision-date of Traffic management plan %title has been deleted.', ['%revision-date' => format_date($this->revision->getRevisionCreationTime()), '%title' => $this->revision->label()]));
    $form_state->setRedirect(
      'entity.traffic_management_plan.canonical',
       ['traffic_management_plan' => $this->revision->id()]
    );
    if ($this->connection->query('SELECT COUNT(DISTINCT vid) FROM {traffic_management_plan_field_revision} WHERE id = :id', [':id' => $this->revision->id()])->fetchField() > 1) {
      $form_state->setRedirect(
        'entity.traffic_management_plan.version_history',
         ['traffic_management_plan' => $this->revision->id()]
      );
    }
  }

}
