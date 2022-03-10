<?php

namespace Drupal\training_overview\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Training overview entities.
 */
class TrainingOverviewViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.
    return $data;
  }

}
