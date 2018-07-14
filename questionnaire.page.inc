<?php

/**
 * @file
 * Contains questionnaire.page.inc.
 *
 * Page callback for Questionnaire entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Questionnaire templates.
 *
 * Default template: questionnaire.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_questionnaire(array &$variables) {
  // Fetch Questionnaire Entity Object.
  $questionnaire = $variables['elements']['#questionnaire'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
