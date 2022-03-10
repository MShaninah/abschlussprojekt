<?php

namespace Drupal\training_overview;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Training overview entities.
 *
 * @ingroup training_overview
 */
class TrainingOverviewListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Training overview ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\training_overview\Entity\TrainingOverview $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.training_overview.edit_form',
      ['training_overview' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
