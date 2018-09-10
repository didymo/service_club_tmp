<?php

namespace Drupal\service_club_tmp\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\service_club_tmp\Entity\TrafficManagementPlanInterface;

/**
 * Class TrafficManagementPlanController.
 *
 *  Returns responses for Traffic management plan routes.
 */
class TrafficManagementPlanController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Traffic management plan  revision.
   *
   * @param int $traffic_management_plan_revision
   *   The Traffic management plan  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($traffic_management_plan_revision) {
    $traffic_management_plan = $this->entityManager()->getStorage('traffic_management_plan')->loadRevision($traffic_management_plan_revision);
    $view_builder = $this->entityManager()->getViewBuilder('traffic_management_plan');

    return $view_builder->view($traffic_management_plan);
  }

  /**
   * Page title callback for a Traffic management plan  revision.
   *
   * @param int $traffic_management_plan_revision
   *   The Traffic management plan  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($traffic_management_plan_revision) {
    $traffic_management_plan = $this->entityManager()->getStorage('traffic_management_plan')->loadRevision($traffic_management_plan_revision);
    return $this->t('Revision of %title from %date', ['%title' => $traffic_management_plan->label(), '%date' => format_date($traffic_management_plan->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a Traffic management plan .
   *
   * @param \Drupal\service_club_tmp\Entity\TrafficManagementPlanInterface $traffic_management_plan
   *   A Traffic management plan  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(TrafficManagementPlanInterface $traffic_management_plan) {
    $account = $this->currentUser();
    $langcode = $traffic_management_plan->language()->getId();
    $langname = $traffic_management_plan->language()->getName();
    $languages = $traffic_management_plan->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $traffic_management_plan_storage = $this->entityManager()->getStorage('traffic_management_plan');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $traffic_management_plan->label()]) : $this->t('Revisions for %title', ['%title' => $traffic_management_plan->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all traffic management plan revisions") || $account->hasPermission('administer traffic management plan entities')));
    $delete_permission = (($account->hasPermission("delete all traffic management plan revisions") || $account->hasPermission('administer traffic management plan entities')));

    $rows = [];

    $vids = $traffic_management_plan_storage->revisionIds($traffic_management_plan);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\service_club_tmp\TrafficManagementPlanInterface $revision */
      $revision = $traffic_management_plan_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $traffic_management_plan->getRevisionId()) {
          $link = $this->l($date, new Url('entity.traffic_management_plan.revision', ['traffic_management_plan' => $traffic_management_plan->id(), 'traffic_management_plan_revision' => $vid]));
        }
        else {
          $link = $traffic_management_plan->link($date);
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
              Url::fromRoute('entity.traffic_management_plan.translation_revert', ['traffic_management_plan' => $traffic_management_plan->id(), 'traffic_management_plan_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.traffic_management_plan.revision_revert', ['traffic_management_plan' => $traffic_management_plan->id(), 'traffic_management_plan_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.traffic_management_plan.revision_delete', ['traffic_management_plan' => $traffic_management_plan->id(), 'traffic_management_plan_revision' => $vid]),
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

    $build['traffic_management_plan_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
