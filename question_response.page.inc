<?php

/**
 * @file
 * Contains question_response.page.inc.
 *
 * Page callback for Question response entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Question response templates.
 *
 * Default template: question_response.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_question_response(array &$variables) {
  // Fetch QuestionResponse Entity Object.
  $question_response = $variables['elements']['#question_response'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
