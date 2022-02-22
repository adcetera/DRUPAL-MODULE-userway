<?php

namespace Drupal\adc_accessibe\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class SettingsForm
 *
 * @package Drupal\adc_accessibe\Form
 */
class SettingsForm extends ConfigFormBase {

  /**
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $config;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'adc_accessibe_config_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'adc_accessibe.settings',
    ];
  }

  /**
   * Constructor
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
    $this->config = $config_factory->get('adc_accessibe.settings');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $settings = $this->configFactory
      ->getEditable('adc_accessibe.settings');

    $form['text'] = [
      '#markup' => '<p>' . $this->t('Configure accessiBe for this site.') . '</p>',
    ];

    $form['chkEnabled'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable accessiBe'),
      '#default_value' => $settings->get('enabled')
    ];

    $form['txtScript'] = [
      '#type' => 'textarea',
      '#title' => $this->t('accessiBe Script'),
      '#description' => $this->t('Enter the accessiBe script tag without "script" tags.'),
      '#rows' => 5,
      '#resizable' => 'both',
      '#default_value' => $settings->get('script'),
      '#required' => FALSE,
      '#states' => [
        'invisible' => [
          ':input[name="chkEnabled"]' => array('checked' => FALSE),
        ]
      ]
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    //parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->configFactory->getEditable('adc_accessibe.settings')
      ->set('enabled', $form_state->getValue('chkEnabled'))
      ->set('script', $form_state->getValue('chkEnabled') ? $form_state->getValue('txtScript') : '')
      ->save();
  }

}
