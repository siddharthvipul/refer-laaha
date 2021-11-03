<?php

namespace Drupal\vss_common_config\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class VssCommonConfigForm.
 *
 * Common configuration form for vss site.
 */
class VssCommonConfigForm extends ConfigFormBase {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Construct function.
   *
   * @param \Drupal\Core\Entity\EntityTypeManager $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(
    EntityTypeManager $entity_type_manager
  ) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'vss_common_config.vsscommonconfig',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'vss_common_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('vss_common_config.vsscommonconfig');
    $commonConfig = $config->get('vss_common_config');
    $form['vsscommonconfig'] = [
      '#type' => 'vertical_tabs',
    ];

    $form['footer_details'] = [
      '#type' => 'details',
      '#title' => 'Footer Contact Information',
      '#group' => 'vsscommonconfig',
    ];

    $form['footer_details']['phone'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Phone'),
      '#description' => $this->t('Enter Phone Number'),
      '#maxlength' => 255,
      '#size' => 64,
      '#default_value' => !empty($commonConfig['phone']) ? $commonConfig['phone'] : '',
    ];
    $form['footer_details']['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#description' => $this->t('Enter Email'),
      '#default_value' => !empty($commonConfig['email']) ? $commonConfig['email'] : '',
    ];
    $form['footer_details']['address'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Address'),
      '#description' => $this->t('Address for Domain'),
      '#default_value' => !empty($commonConfig['address']) ? $commonConfig['address'] : '',
      '#maxlength' => 255,
      '#size' => 64,
    ];

    $form['disclaimer'] = [
      '#type' => 'details',
      '#title' => $this->t('Disclaimer'),
      '#group' => 'vsscommonconfig',
    ];

    $form['disclaimer']['disclaimer_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Ttile'),
      '#default_value' => !empty($commonConfig['disclaimer_title']) ? $commonConfig['disclaimer_title'] : '',
    ];

    $form['disclaimer']['disclaimer_description'] = [
      '#type' => 'text_format',
      '#format' => 'full_html',
      '#title' => $this->t('Message'),
      '#default_value' => !empty($commonConfig['disclaimer_description']) ? $commonConfig['disclaimer_description']['value'] : '',
    ];
    $form['disclaimer']['disclaimer_image'] = [
      '#type'                 => 'managed_file',
      '#upload_location'      => 'public://disclaimer/',
      '#multiple'             => FALSE,
      '#description'          => $this->t('Allowed extensions: gif png jpg jpeg'),
      '#upload_validators'    => [
        'file_validate_is_image'      => [],
        'file_validate_extensions'    => ['gif png jpg jpeg'],
        'file_validate_size'          => [25600000],
      ],
      '#title'                => $this->t('Upload an image file.'),
      '#default_value' => !empty($commonConfig['disclaimer_image']) ? [$commonConfig['disclaimer_image'][0]] : '',
    ];

    $form['header_phone'] = [
      '#type' => 'details',
      '#title' => 'Header Phone Information',
      '#group' => 'vsscommonconfig',
    ];
    $form['header_phone']['header_country_code'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Country Code'),
      '#description' => $this->t('Enter Country Code'),
      '#maxlength' => 255,
      '#size' => 64,
      '#default_value' => !empty($commonConfig['header_country_code']) ? $commonConfig['header_country_code'] : '',
    ];
    $form['header_phone']['header_phone'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Header Phone'),
      '#description' => $this->t('Enter Header Phone Number'),
      '#maxlength' => 255,
      '#size' => 64,
      '#default_value' => !empty($commonConfig['header_phone']) ? $commonConfig['header_phone'] : '',
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * Validate phone number.
   */
  public function validatePhoneNumber($phone) {
    if (preg_match("/[a-z]/i", $phone)) {
      return FALSE;
    }
    if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_¬]/', $phone)) {
      return FALSE;
    }
    if (!filter_var($phone, FILTER_SANITIZE_NUMBER_INT)) {
      return FALSE;
    }
    // Allow +, - and . in phone number.
    $filtered_phone_number = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
    // Remove "-" from number.
    $phone_to_check = str_replace("-", "", $filtered_phone_number);
    // Check the lenght of number
    // This can be customized if you want phone number from a specific country.
    if (strlen($phone_to_check) < 10 || strlen($phone_to_check) > 14) {
      return FALSE;
    }
    else {
      return TRUE;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $phone = $form_state->getValue('phone');
    $phoneHeader = $form_state->getValue('header_phone');
    if (!empty($phoneHeader) && !$this->validatePhoneNumber($phoneHeader)) {
      $form_state->setErrorByName(
        "header_phone",
        $this->t('Please enter valid phone number in Header Phone field.')
      );
    }
    if (!empty($phone) && !$this->validatePhoneNumber($phone)) {
      $form_state->setErrorByName("phone",
       $this->t('Please enter valid phone number.'));
    }
    $email = $form_state->getValue('email');
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $form_state->setErrorByName(
        "email",
        $this->t('Please enter valid email id.')
      );
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $config = $this->config('vss_common_config.vsscommonconfig');
    $config->set('vss_common_config', $form_state->getValues());
    $config->save();
    if ($image = $form_state->getValue('disclaimer_image')) {
      $file = $this->entityTypeManager->getStorage('file')->load($image[0]);
      $file->setPermanent();
      $file->save();
    }
  }

}
