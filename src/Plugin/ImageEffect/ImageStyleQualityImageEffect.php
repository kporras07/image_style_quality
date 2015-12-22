<?php

/**
 * @file
 * Contains \Drupal\image_style_quality\Plugin\ImageEffect\ImageStyleQualityImageEffect.
 */

namespace Drupal\image_style_quality\Plugin\ImageEffect;

use Drupal\Core\Config\ImmutableConfig;
use Drupal\Core\Image\ImageInterface;
use Drupal\image\ConfigurableImageEffectBase;
use Drupal\Core\Form\FormStateInterface;
use Psr\Log\LoggerInterface;
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
class ImageStyleQualityImageEffect extends ConfigurableImageEffectBase {

  /**
   * The GD image config object.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $gd_config;

  /**
   * {@inheritdoc}
   */
  public function applyEffect(ImageInterface $image) {
    $this->gd_config->setModuleOverride([
      'jpeg_quality' => $this->configuration['quality'],
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['quality'] = [
      '#type' => 'number',
      '#title' => t('Quality'),
      '#description' => t('Define the image quality for JPEG manipulations. Ranges from 0 to 100. Higher values mean better image quality but bigger files.'),
      '#min' => 0,
      '#max' => 100,
      '#default_value' => $this->configuration['quality'],
      '#field_suffix' => t('%'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->configuration['quality'] = $form_state->getValue('quality');
  }

  /**
   * {@inheritdoc}
   */
  public function getSummary() {
    return [
      '#markup' => '(' . $this->configuration['quality'] . '% ' . $this->t('Quality') . ')',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'quality' => 75,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, LoggerInterface $logger, ImmutableConfig $gd_config) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $logger);
    $this->gd_config = $gd_config;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('logger.factory')->get('image'),
      $container->get('config.factory')->get('system.image.gd')
    );
  }

}
