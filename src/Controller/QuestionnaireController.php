<?php

namespace Drupal\service_club_tmp\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\service_club_tmp\Entity\QuestionnaireInterface;

/**
 * Class QuestionnaireController.
 *
 *  Returns responses for Questionnaire routes.
 */
class QuestionnaireController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Questionnaire  revision.
   *
   * @param int $questionnaire_revision
   *   The Questionnaire  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($questionnaire_revision) {
    $questionnaire = $this->entityManager()->getStorage('questionnaire')->loadRevision($questionnaire_revision);
    $view_builder = $this->entityManager()->getViewBuilder('questionnaire');

    return $view_builder->view($questionnaire);
  }

  /**
   * Page title callback for a Questionnaire  revision.
   *
   * @param int $questionnaire_revision
   *   The Questionnaire  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($questionnaire_revision) {
    $questionnaire = $this->entityManager()->getStorage('questionnaire')->loadRevision($questionnaire_revision);
    return $this->t('Revision of %title from %date', ['%title' => $questionnaire->label(), '%date' => format_date($questionnaire->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a Questionnaire .
   *
   * @param \Drupal\service_club_tmp\Entity\QuestionnaireInterface $questionnaire
   *   A Questionnaire  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(QuestionnaireInterface $questionnaire) {
    $account = $this->currentUser();
    $langcode = $questionnaire->language()->getId();
    $langname = $questionnaire->language()->getName();
    $languages = $questionnaire->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $questionnaire_storage = $this->entityManager()->getStorage('questionnaire');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $questionnaire->label()]) : $this->t('Revisions for %title', ['%title' => $questionnaire->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all questionnaire revisions") || $account->hasPermission('administer questionnaire entities')));
    $delete_permission = (($account->hasPermission("delete all questionnaire revisions") || $account->hasPermission('administer questionnaire entities')));

    $rows = [];

    $vids = $questionnaire_storage->revisionIds($questionnaire);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\service_club_tmp\QuestionnaireInterface $revision */
      $revision = $questionnaire_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $questionnaire->getRevisionId()) {
          $link = $this->l($date, new Url('entity.questionnaire.revision', ['questionnaire' => $questionnaire->id(), 'questionnaire_revision' => $vid]));
        }
        else {
          $link = $questionnaire->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => \Drupal::service('renderer')->renderPlain($username),
              'message' => ['#markup' => $revision->getRevisionLogMessage(), '#allowed_tags' => Xss::getHtmlTagList()],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.questionnaire.translation_revert', ['questionnaire' => $questionnaire->id(), 'questionnaire_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.questionnaire.revision_revert', ['questionnaire' => $questionnaire->id(), 'questionnaire_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.questionnaire.revision_delete', ['questionnaire' => $questionnaire->id(), 'questionnaire_revision' => $vid]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['questionnaire_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
