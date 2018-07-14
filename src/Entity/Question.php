<?php

namespace Drupal\service_club_tmp\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the Question entity.
 *
 * @ConfigEntityType(
 *   id = "question",
 *   label = @Translation("Question"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\service_club_tmp\QuestionListBuilder",
 *     "form" = {
 *       "add" = "Drupal\service_club_tmp\Form\QuestionForm",
 *       "edit" = "Drupal\service_club_tmp\Form\QuestionForm",
 *       "delete" = "Drupal\service_club_tmp\Form\QuestionDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\service_club_tmp\QuestionHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "question",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "weight" = "weight",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/config/tmp/question/{question}",
 *     "add-form" = "/admin/config/tmp/question/add",
 *     "edit-form" = "/admin/config/tmp/question/{question}/edit",
 *     "delete-form" = "/admin/config/tmp/question/{question}/delete",
 *     "collection" = "/admin/config/tmp/question"
 *   }
 * )
 */
class Question extends ConfigEntityBase implements QuestionInterface {

  /**
   * The Question ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Question label.
   *
   * @var string
   */
  protected $label;

  /**
   * Weight of this question in the list of questionnaire questions.
   *
   * The weight determines the order that the questions will be displayed
   * to the user.
   *
   * @var int
   */
  protected $weight = 0;

}
