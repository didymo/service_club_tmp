<?php

namespace Drupal\service_club_tmp\Form;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Url;
use Drupal\service_club_tmp\Entity\EventClassInterface;
use Drupal\service_club_tmp\Entity\EventClassSection;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides terms overview form for a taxonomy vocabulary.
 *
 * @internal
 */
class OverviewEventClassSections extends FormBase {

  /**
   * The module handler service.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * The entity manager.
   *
   * @var \Drupal\Core\Entity\EntityManagerInterface
   */
  protected $entityManager;

  /**
   * The term storage handler.
   *
   * @var \Drupal\taxonomy\TermStorageInterface
   */
  protected $storageController;

  /**
   * The section list builder.
   *
   * @var \Drupal\Core\Entity\EntityListBuilderInterface
   */
  protected $sectionListBuilder;

  /**
   * The renderer service.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * Constructs an OverviewEventClassSections object.
   *
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler service.
   * @param \Drupal\Core\Entity\EntityManagerInterface $entity_manager
   *   The entity manager service.
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The renderer service.
   */
  public function __construct(ModuleHandlerInterface $module_handler, EntityManagerInterface $entity_manager, RendererInterface $renderer = NULL) {
    $this->moduleHandler = $module_handler;
    $this->entityManager = $entity_manager;
    $this->storageController = $entity_manager->getStorage('event_class_section');
    $this->sectionListBuilder = $entity_manager->getListBuilder('event_class_section');
    $this->renderer = $renderer ?: \Drupal::service('renderer');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('module_handler'),
      $container->get('entity.manager'),
      $container->get('renderer')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'event_class_overview_sections';
  }

  /**
   * Form constructor.
   *
   * Display all the text sections of an event class, with options to edit
   * each one. The form is made drag and drop by the theme function.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * @param \Drupal\service_club_tmp\EventClassInterface $event_class
   *   The event class to display the overview form for.
   *
   * @return array
   *   The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state, EventClassInterface $event_class = NULL) {

    $sections = $event_class->getEventClassSections();
    $section_index = 0;
    // An array of the sections to be displayed on this page.
    $current_page = [];

    do {
      // In case this tree is completely empty.
      if (empty($sections[$section_index])) {
        break;
      }

      $section = $sections[$section_index];
      $key = 'sid:' . $section->id();

      $current_page[$key] = $section;
    } while (isset($sections[++$section_index]));

    $errors = $form_state->getErrors();
    $row_position = 0;

    // Build the actual form.
    $access_control_handler = $this->entityManager->getAccessControlHandler('event_class_section');
    $create_access = $access_control_handler->createAccess($event_class->id(), NULL, [], TRUE);
    if ($create_access->isAllowed()) {
      $empty = $this->t('No event class sections available. <a href=":link">Add section</a>.', [':link' => Url::fromRoute('entity.event_class_section.add_form', ['event_class' => $event_class->id()])->toString()]);
    }
    else {
      $empty = $this->t('No event class sections available.');
    }
    $form['sections'] = [
      '#type' => 'table',
      '#empty' => $empty,
      '#header' => [
        'term' => $this->t('Name'),
        'operations' => $this->t('Operations'),
        'weight' => $this->t('Weight'),
      ],
    ];
    $this->renderer->addCacheableDependency($form['sections'], $create_access);

    // Only allow access to changing weights if the user has update access for
    // all terms.
    $change_weight_access = AccessResult::allowed();
    foreach ($current_page as $key => $section) {
      $form['sections'][$key] = [
        'section' => [],
        'operations' => [],
        'weight' => [],
      ];
      /** @var $term \Drupal\Core\Entity\EntityInterface */
      $term = $this->entityManager->getTranslationFromContext($section);
      $form['sections'][$key]['#section'] = $section;
      $form['sections'][$key]['section'] = [
        '#type' => 'link',
        '#title' => $section->getHeading(),
        '#url' => $section->urlInfo(),
      ];

      $update_access = $section->access('update', NULL, TRUE);
      $change_weight_access = $change_weight_access->andIf($update_access);

      if ($update_access->isAllowed()) {
        $form['sections'][$key]['weight'] = [
          '#type' => 'weight',
          '#title' => $this->t('Weight for event class section'),
          '#title_display' => 'invisible',
          '#default_value' => $section->getWeight(),
          '#attributes' => ['class' => ['section-weight']],
        ];
      }

      if ($operations = $this->sectionListBuilder->getOperations($section)) {
        $form['sections'][$key]['operations'] = [
          '#type' => 'operations',
          '#links' => $operations,
        ];
      }

      // Add an error class if this row contains a form error.
      foreach ($errors as $error_key => $error) {
        if (strpos($error_key, $key) === 0) {
          $form['sections'][$key]['#attributes']['class'][] = 'error';
        }
      }
      $row_position++;
    }

    $form['actions']['#type'] = 'actions';
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Save'),
      '#button_type' => 'primary',
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    /*foreach ($form_state->getValue($this->entitiesKey) as $id => $value) {
      if (isset($this->entities[$id]) && $this->entities[$id]->get($this->weightKey) != $value['weight']) {
        // Save entity only when its weight was changed.
        $this->entities[$id]->set($this->weightKey, $value['weight']);
        $this->entities[$id]->save();
      }
    }*/
    drupal_set_message($this->t('The configuration options have been saved.'));
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

}
