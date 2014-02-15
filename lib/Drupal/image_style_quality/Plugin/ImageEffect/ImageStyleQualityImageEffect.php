<?php

/**
 * @file
 * Contains \Drupal\image_style_lifestyle\Plugin\ImageEffect\ImageStyleQualityImageEffect
 */

namespace Drupal\image_style_quality\Plugin\ImageEffect;

use Drupal\Core\Config\ConfigFactory;
use Drupal\image\ImageEffectBase;
use Drupal\image\ConfigurableImageEffectInterface;
use Drupal\Core\Image\ImageInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Allows you to change the quality of an image, per image style.
 *
 * @ImageEffect(
 *   id = "image_style_quality",
 *   label = @Translation("Image Style Quality"),
 *   description = @Translation("Allows you to change the quality of an image, per image style.")
 * )
 */
class ImageStyleQualityImageEffect extends ImageEffectBase implements ConfigurableImageEffectInterface, ContainerFactoryPluginInterface {

  public function applyEffect(ImageInterface $image) {

  }

  public function getForm() {
    $form['image_jpeg_quality'] = array(
      '#type' => 'number',
      '#title' => t('JPEG quality'),
      '#description' => t('Define the image quality for JPEG manipulations. Ranges from 0 to 100. Higher values mean better image quality but bigger files.'),
      '#min' => 0,
      '#max' => 100,
      '#default_value' => \Drupal::config('system.image.gd')->get('jpeg_quality'),
      '#field_suffix' => t('%'),
    );
    return $form;
  }


  public function __construct(array $configuration, $plugin_id, array $plugin_definition, ConfigFactoryInterface $config) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->config = $config;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, array $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory')
    );
  }

}
