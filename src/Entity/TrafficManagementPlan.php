<?php

namespace Drupal\service_club_tmp\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Traffic management plan entity.
 *
 * @ingroup service_club_tmp
 *
 * @ContentEntityType(
 *   id = "traffic_management_plan",
 *   label = @Translation("Traffic management plan"),
 *   handlers = {
 *     "storage" = "Drupal\service_club_tmp\TrafficManagementPlanStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\service_club_tmp\TrafficManagementPlanListBuilder",
 *     "views_data" = "Drupal\service_club_tmp\Entity\TrafficManagementPlanViewsData",
 *     "translation" = "Drupal\service_club_tmp\TrafficManagementPlanTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\service_club_tmp\Form\TrafficManagementPlanForm",
 *       "add" = "Drupal\service_club_tmp\Form\TrafficManagementPlanForm",
 *       "edit" = "Drupal\service_club_tmp\Form\TrafficManagementPlanForm",
 *       "delete" = "Drupal\service_club_tmp\Form\TrafficManagementPlanDeleteForm",
 *     },
 *     "access" = "Drupal\service_club_tmp\TrafficManagementPlanAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\service_club_tmp\TrafficManagementPlanHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "traffic_management_plan",
 *   data_table = "traffic_management_plan_field_data",
 *   revision_table = "traffic_management_plan_revision",
 *   revision_data_table = "traffic_management_plan_field_revision",
 *   translatable = TRUE,
 *   admin_permission = "administer traffic management plan entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "vid",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/traffic_management_plan/{traffic_management_plan}",
 *     "add-form" = "/admin/structure/traffic_management_plan/add",
 *     "edit-form" = "/admin/structure/traffic_management_plan/{traffic_management_plan}/edit",
 *     "delete-form" = "/admin/structure/traffic_management_plan/{traffic_management_plan}/delete",
 *     "version-history" = "/admin/structure/traffic_management_plan/{traffic_management_plan}/revisions",
 *     "revision" = "/admin/structure/traffic_management_plan/{traffic_management_plan}/revisions/{traffic_management_plan_revision}/view",
 *     "revision_revert" = "/admin/structure/traffic_management_plan/{traffic_management_plan}/revisions/{traffic_management_plan_revision}/revert",
 *     "revision_delete" = "/admin/structure/traffic_management_plan/{traffic_management_plan}/revisions/{traffic_management_plan_revision}/delete",
 *     "translation_revert" = "/admin/structure/traffic_management_plan/{traffic_management_plan}/revisions/{traffic_management_plan_revision}/revert/{langcode}",
 *     "collection" = "/admin/structure/traffic_management_plan",
 *   },
 *   field_ui_base_route = "traffic_management_plan.settings"
 * )
 */
class TrafficManagementPlan extends RevisionableContentEntityBase implements TrafficManagementPlanInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function urlRouteParameters($rel) {
    $uri_route_parameters = parent::urlRouteParameters($rel);

    if ($rel === 'revision_revert' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }
    elseif ($rel === 'revision_delete' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }

    return $uri_route_parameters;
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);

    foreach (array_keys($this->getTranslationLanguages()) as $langcode) {
      $translation = $this->getTranslation($langcode);

      // If no owner has been set explicitly, make the anonymous user the owner.
      if (!$translation->getOwner()) {
        $translation->setOwnerId(0);
      }
    }

    // If no revision author has been set explicitly, make the TMP owner the
    // revision author.
    if (!$this->getRevisionUser()) {
      $this->setRevisionUserId($this->getOwnerId());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? TRUE : FALSE);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getBounds() {
    return array(
      "leftTop" => array(
        "latitude" => (double) $this->get('north_bound')->value,
        "longitude" => (double) $this->get('west_bound')->value,
      ),
      "rightBottom" => array(
        "latitude" => (double) $this->get('south_bound')->value,
        "longitude" => (double) $this->get('east_bound')->value,
      ),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function setBounds(array $bounds) {
    $this->set('north_bound', $bounds["leftTop"]["latitude"]);
    $this->set('west_bound', $bounds["leftTop"]["longitude"]);
    $this->set('south_bound', $bounds["rightBottom"]["latitude"]);
    $this->set('east_bound', $bounds["rightBottom"]["longitude"]);
    $this->save();
  }

  /**
   * {@inheritdoc}
   */
  public function getObjects() {
    return $this->get('objects')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setObjects($objects) {
    $this->set('objects', "$objects");
    $this->save();
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Traffic management plan entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Traffic management plan entity.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Traffic management plan is published.'))
      ->setRevisionable(TRUE)
      ->setDefaultValue(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -3,
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['revision_translation_affected'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Revision translation affected'))
      ->setDescription(t('Indicates if the last edit of a translation belongs to current revision.'))
      ->setReadOnly(TRUE)
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE);

    $fields['north_bound'] = BaseFieldDefinition::create('float')
      ->setLabel(t('North Bound'))
      ->setDescription(t('The North Bound (Latitude) of the map.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'min' => -90,
        'max' => 90,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'float',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'float_textfield',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['east_bound'] = BaseFieldDefinition::create('float')
      ->setLabel(t('East Bound'))
      ->setDescription(t('The East Bound (Longitude) of the map.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'min' => -180,
        'max' => 180,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'float',
        'weight' => 1,
      ])
      ->setDisplayOptions('form', [
        'type' => 'float_textfield',
        'weight' => 1,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['south_bound'] = BaseFieldDefinition::create('float')
      ->setLabel(t('South Bound'))
      ->setDescription(t('The South Bound (Latitude) of the map.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'min' => -90,
        'max' => 90,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'float',
        'weight' => 2,
      ])
      ->setDisplayOptions('form', [
        'type' => 'float_textfield',
        'weight' => 2,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['west_bound'] = BaseFieldDefinition::create('float')
      ->setLabel(t('West Bound'))
      ->setDescription(t('The West Bound (Longitude) of the map.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'min' => -180,
        'max' => 180,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'float',
        'weight' => 3,
      ])
      ->setDisplayOptions('form', [
        'type' => 'float_textfield',
        'weight' => 3,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['objects'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Objects'))
      ->setDescription(t('The json string containing the map objects.'))
      ->setRevisionable(TRUE)
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'textfield',
        'weight' => 4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'textfield',
        'weight' => 4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    return $fields;
  }

}
