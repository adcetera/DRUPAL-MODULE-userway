<?php

namespace Drupal\userway_embed\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class SettingsForm
 *
 * @package Drupal\userway_embed\Form
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
    return 'userway_embed_config_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'userway_embed.settings',
    ];
  }

  /**
   * Constructor
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
    $this->config = $config_factory->get('userway_embed.settings');
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
      ->getEditable('userway_embed.settings');

    $form['text'] = [
      '#markup' => '<p>' . $this->t('Configure Userway for this site.') . '</p>',
    ];

    $form['chkEnabled'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable Userway'),
      '#default_value' => $settings->get('enabled')
    ];

    $form['txtScript'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Userway Script'),
      '#description' => $this->t('Enter the Userway script tag without "script" tags.'),
      '#rows' => 5,
      '#resizable' => 'both',
      '#default_value' => $settings->get('script'),
      '#states' => [
        'invisible' => [
          ':input[name="chkEnabled"]' => array('checked' => FALSE),
        ]
      ],
      '#required' => [
        ':input[name="chkCustomTrigger"]' => array('checked' => TRUE)
      ],
    ];

    $form['chkCustomTrigger'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Use a custom trigger'),
      '#default_value' => $settings->get('custom_trigger_enabled'),
      '#states' => [
        'invisible' => [
          ':input[name="chkEnabled"]' => array('checked' => FALSE),
        ]
      ]
    ];

    $form['txtCustomTrigger'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Custom trigger'),
      '#description' => $this->t('Enter the ID or class defined in Userway under "custom trigger."'),
      '#default_value' => $settings->get('custom_trigger'),
      '#maxlength' => 2048,
      '#states' => [
        'invisible' => [
          ':input[name="chkCustomTrigger"]' => array('checked' => FALSE),
        ]
      ],
      '#required' => [
        ':input[name="chkCustomTrigger"]' => array('checked' => TRUE)
      ],
    ];

    $form['txtCustomTriggerSelector'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Custom trigger selector'),
      '#description' => $this->t('Enter the selector that should trigger the Userway widget.'),
      '#default_value' => $settings->get('custom_trigger_selector'),
      '#maxlength' => 2048,
      '#states' => [
        'invisible' => [
          ':input[name="chkCustomTrigger"]' => array('checked' => FALSE),
        ]
      ],
      '#required' => [
        ':input[name="chkCustomTrigger"]' => array('checked' => TRUE)
      ],
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->configFactory->getEditable('userway_embed.settings')
      ->set('enabled', $form_state->getValue('chkEnabled'))
      ->set('script', $form_state->getValue('chkEnabled') ? $form_state->getValue('txtScript') : '')
      ->set('custom_trigger_enabled', $form_state->getValue('chkCustomTrigger'))
      ->set('custom_trigger', $form_state->getValue('chkCustomTrigger') ? $form_state->getValue('txtCustomTrigger') : '')
      ->set('custom_trigger_selector', $form_state->getValue('chkCustomTrigger') ? $form_state->getValue('txtCustomTriggerSelector') : '')
      ->save();
  }

}
