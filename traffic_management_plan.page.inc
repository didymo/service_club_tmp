<?php

/**
 * @file
 * Contains traffic_management_plan.page.inc.
 *
 * Page callback for Traffic management plan entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Traffic management plan templates.
 *
 * Default template: traffic_management_plan.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_traffic_management_plan(array &$variables) {
  // Fetch TrafficManagementPlan Entity Object.
  $traffic_management_plan = $variables['elements']['#traffic_management_plan'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
