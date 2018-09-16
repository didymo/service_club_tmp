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

      /*$form['sections'][$key]['#attributes']['class'] = [];
      if ($parent_fields) {
        $form['sections'][$key]['#attributes']['class'][] = 'draggable';
      }*/

      // Add an error class if this row contains a form error.
      foreach ($errors as $error_key => $error) {
        if (strpos($error_key, $key) === 0) {
          $form['sections'][$key]['#attributes']['class'][] = 'error';
        }
      }
      $row_position++;
    }

    /*$this->renderer->addCacheableDependency($form['terms'], $change_weight_access);
    if ($change_weight_access->isAllowed()) {
      if ($parent_fields) {
        $form['terms']['#tabledrag'][] = [
          'action' => 'match',
          'relationship' => 'parent',
          'group' => 'term-parent',
          'subgroup' => 'term-parent',
          'source' => 'term-id',
          'hidden' => FALSE,
        ];
        $form['terms']['#tabledrag'][] = [
          'action' => 'depth',
          'relationship' => 'group',
          'group' => 'term-depth',
          'hidden' => FALSE,
        ];
        $form['terms']['#attached']['library'][] = 'taxonomy/drupal.taxonomy';
        $form['terms']['#attached']['drupalSettings']['taxonomy'] = [
          'backStep' => $back_step,
          'forwardStep' => $forward_step,
        ];
      }
      $form['terms']['#tabledrag'][] = [
        'action' => 'order',
        'relationship' => 'sibling',
        'group' => 'term-weight',
      ];
    }*/

    /*if (($taxonomy_vocabulary->getHierarchy() !== VocabularyInterface::HIERARCHY_MULTIPLE && count($tree) > 1) && $change_weight_access->isAllowed()) {
      $form['actions'] = ['#type' => 'actions', '#tree' => FALSE];
      $form['actions']['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Save'),
        '#button_type' => 'primary',
      ];
      $form['actions']['reset_alphabetical'] = [
        '#type' => 'submit',
        '#submit' => ['::submitReset'],
        '#value' => $this->t('Reset to alphabetical'),
      ];
    }

    $form['pager_pager'] = ['#type' => 'pager'];*/
    return $form;
  }

  /**
   * Form submission handler.
   *
   * Rather than using a textfield or weight field, this form depends entirely
   * upon the order of form elements on the page to determine new weights.
   *
   * Because there might be hundreds or thousands of taxonomy terms that need to
   * be ordered, terms are weighted from 0 to the number of terms in the
   * vocabulary, rather than the standard -10 to 10 scale. Numbers are sorted
   * lowest to highest, but are not necessarily sequential. Numbers may be
   * skipped when a term has children so that reordering is minimal when a child
   * is added or removed from a term.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
/*  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Sort term order based on weight.
    uasort($form_state->getValue('terms'), ['Drupal\Component\Utility\SortArray', 'sortByWeightElement']);

    $vocabulary = $form_state->get(['taxonomy', 'vocabulary']);
    // Update the current hierarchy type as we go.
    $hierarchy = VocabularyInterface::HIERARCHY_DISABLED;

    $changed_terms = [];
    $tree = $this->storageController->loadTree($vocabulary->id(), 0, NULL, TRUE);

    if (empty($tree)) {
      return;
    }

    // Build a list of all terms that need to be updated on previous pages.
    $weight = 0;
    $term = $tree[0];
    while ($term->id() != $form['#first_tid']) {
      if ($term->parents[0] == 0 && $term->getWeight() != $weight) {
        $term->setWeight($weight);
        $changed_terms[$term->id()] = $term;
      }
      $weight++;
      $hierarchy = $term->parents[0] != 0 ? VocabularyInterface::HIERARCHY_SINGLE : $hierarchy;
      $term = $tree[$weight];
    }

    // Renumber the current page weights and assign any new parents.
    $level_weights = [];
    foreach ($form_state->getValue('terms') as $tid => $values) {
      if (isset($form['terms'][$tid]['#term'])) {
        $term = $form['terms'][$tid]['#term'];
        // Give terms at the root level a weight in sequence with terms on previous pages.
        if ($values['term']['parent'] == 0 && $term->getWeight() != $weight) {
          $term->setWeight($weight);
          $changed_terms[$term->id()] = $term;
        }
        // Terms not at the root level can safely start from 0 because they're all on this page.
        elseif ($values['term']['parent'] > 0) {
          $level_weights[$values['term']['parent']] = isset($level_weights[$values['term']['parent']]) ? $level_weights[$values['term']['parent']] + 1 : 0;
          if ($level_weights[$values['term']['parent']] != $term->getWeight()) {
            $term->setWeight($level_weights[$values['term']['parent']]);
            $changed_terms[$term->id()] = $term;
          }
        }
        // Update any changed parents.
        if ($values['term']['parent'] != $term->parents[0]) {
          $term->parent->target_id = $values['term']['parent'];
          $changed_terms[$term->id()] = $term;
        }
        $hierarchy = $term->parents[0] != 0 ? VocabularyInterface::HIERARCHY_SINGLE : $hierarchy;
        $weight++;
      }
    }

    // Build a list of all terms that need to be updated on following pages.
    for ($weight; $weight < count($tree); $weight++) {
      $term = $tree[$weight];
      if ($term->parents[0] == 0 && $term->getWeight() != $weight) {
        $term->parent->target_id = $term->parents[0];
        $term->setWeight($weight);
        $changed_terms[$term->id()] = $term;
      }
      $hierarchy = $term->parents[0] != 0 ? VocabularyInterface::HIERARCHY_SINGLE : $hierarchy;
    }

    // Save all updated terms.
    foreach ($changed_terms as $term) {
      $term->save();
    }

    // Update the vocabulary hierarchy to flat or single hierarchy.
    if ($vocabulary->getHierarchy() != $hierarchy) {
      $vocabulary->setHierarchy($hierarchy);
      $vocabulary->save();
    }
    drupal_set_message($this->t('The configuration options have been saved.'));
  }*/

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Display result.
    foreach ($form_state->getValues() as $key => $value) {
      drupal_set_message($key . ': ' . $value);
    }

  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

}
