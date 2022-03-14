<?php

namespace Drupal\erpw_location\Form;

use Drupal\Core\Url;
use Drupal\Core\Database\Connection;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\erpw_location\LocationCookie;
use Drupal\erpw_location\LocationService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class LocationListForm.
 */
class UserLocationForm extends LocationListForm {

  /**
   * Drupal\Core\Session\AccountInterface definition.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * The Messenger service.
   *
   * @var \Drupal\erpw_location\LocationService
   */
  protected $locationService;

  /**
   * The form builder.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * The cookie as a service.
   *
   * @var \Drupal\erpw_location\LocationCookie
   */
  protected $locationCookie;

  /**
   * The tempstore factory.
   *
   * @var \Drupal\Core\TempStore\PrivateTempStoreFactory
   */
  protected $tempStoreFactory;

  /**
   * A request stack symfony instance.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * {@inheritdoc}
   */
  public function __construct(
    Connection $database,
    EntityTypeManagerInterface $entity_type_manager,
    AccountInterface $current_user,
    FormBuilderInterface $form_builder,
    LocationCookie $location_cookie,
    PrivateTempStoreFactory $temp_store_factory,
    LocationService $location_service,
    RequestStack $request_stack) {
    $this->database = $database;
    $this->entityTypeManager = $entity_type_manager;
    $this->currentUser = $current_user;
    $this->formBuilder = $form_builder;
    $this->locationCookie = $location_cookie;
    $this->tempStoreFactory = $temp_store_factory->get('erpw_location_collection');
    $this->locationService = $location_service;
    $this->requestStack = $request_stack;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database'),
      $container->get('entity_type.manager'),
      $container->get('current_user'),
      $container->get('form_builder'),
      $container->get('erpw_location.location_cookie'),
      $container->get('tempstore.private'),
      $container->get('erpw_location.location_services'),
      $container->get('request_stack'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'user_location_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL, $page = "") {
    $form = parent::buildForm($form, $form_state);
    $form['location_level']['button'] = [
      '#type' => 'submit',
      '#title' => "Change",
      '#value' => 'DONE',
      '#attributes' => [
        'class' => [
          'use-ajax',
          'arrow-btn',
        ],
      ],
    ];
    $form['location_level_page'] = [
      '#type' => 'hidden',
      '#value' => $page,
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $levels = $form_state->getValues();

    if (!empty($levels['level_4'])) {
      $location_value = $levels['level_4'];
    }
    elseif (!empty($levels['level_3'])) {
      $location_value = $levels['level_3'];
    }
    elseif (!empty($levels['level_2'])) {
      $location_value = $levels['level_2'];
    }
    elseif (!empty($levels['level_1'])) {
      $location_value = $levels['level_1'];
    }
    elseif (!empty($levels['location_options'])) {
      $location_entity = $this->entityTypeManager->getStorage('location')->load($levels['location_options']);
      if (!empty($location_entity->get('field_location_taxonomy_term')->getValue())) {
        $location_value = $location_entity->get('field_location_taxonomy_term')->getValue()[0]['target_id'];
      }
    }

    if ($levels['location_level_page'] == 'location') {
      $url = Url::fromUri('internal:/manage-location/' . $levels['location_options'] . '/' . $location_value);
      $form['location_level']['button'] = $form_state->setRedirectUrl($url);
    }
    else {
      if (empty($this->locationCookie->getCookieValue())) {
        $this->locationCookie->setCookieValue(base64_encode('country_tid_' . time()));
        $this->tempStoreFactory->set(base64_decode($this->locationCookie->getCookieValue()), $location_value);
      }
      else {
        $this->tempStoreFactory->set(base64_decode($this->locationCookie->getCookieValue()), $location_value);
      }
      $domain_current_url = explode(".", $this->requestStack->getCurrentRequest()->server->get('SERVER_NAME'));
      $domain_slice = array_slice($domain_current_url, -2);
      $domain_site = '.' . $domain_slice[0] . '.' . $domain_slice[1];

      setcookie('location_tid', $location_value, strtotime('+1 year'), '/', $domain_site, FALSE);
      $url = Url::fromRoute('<front>');
      $form['location_level']['button'] = $form_state->setRedirectUrl($url);
    }
  }

}
