<?php

namespace Drupal\vss_common_config;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\language\ConfigurableLanguageManagerInterface;
use Drupal\Core\Config\CachedStorage;
use Drupal\domain_config_ui\Config\ConfigFactory;
use Drupal\domain\DomainNegotiator;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\file\Entity\File;

/**
 * Class VssCommonService.
 */
class VssCommonService implements VssCommonInterface {

  /**
   * Drupal\Core\Entity\EntityTypeManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Drupal\language\ConfigurableLanguageManagerInterface definition.
   *
   * @var \Drupal\language\ConfigurableLanguageManagerInterface
   */
  protected $languageManager;

  /**
   * \Symfony\Component\HttpFoundation\RequestStack definition.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $request;

  /**
   * Drupal\domain_config_ui\Config\ConfigFactory definition.
   *
   * @var \Drupal\domain_config_ui\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * The domain negotiator.
   *
   * @var \Drupal\domain\DomainNegotiator
   */
  protected $domainNegotiator;

  /**
   * Constructs a new VssCommonService object.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, ConfigurableLanguageManagerInterface $language_manager, RequestStack $request, ConfigFactory $config_factory, DomainNegotiator $domainNegotiator) {
    $this->entityTypeManager = $entity_type_manager;
    $this->languageManager = $language_manager;
    $this->request = $request;
    $this->configFactory = $config_factory;
    $this->domainNegotiator = $domainNegotiator;
  }

  /**
   * Get domain specific config.
   */
  public function getVssDomainWithLanguageConfiguration() : array {
    $activeDomain = $this->domainNegotiator->getActiveDomain();
    $langId = $this->languageManager->getCurrentLanguage()->getId();
    $host = $this->request->getCurrentRequest()->getHost();
    $rawData = [];
    if ($activeDomain->getHostName() === $host) {
      $rawData = $this->configFactory->get('domain.config.' . $activeDomain->id() .'.' . $langId .'.vss_common_config.vsscommonconfig')->getRawData();
    }
    return $rawData;
  }

  /**
   * Get domain specific config.
   */
  public function getVssDomainWithoutLanguageConfiguration() : array {
    $activeDomain = $this->domainNegotiator->getActiveDomain();
    $langId = $this->languageManager->getCurrentLanguage()->getId();
    $host = $this->request->getCurrentRequest()->getHost();
    $rawData = [];
      if ($activeDomain->getHostName() === $host) {
        $rawData = $this->configFactory->get('domain.config.' . $activeDomain->id() . '.vss_common_config.vsscommonconfig')->getRawData();
      }
    return $rawData;
  }

  /**
   * Get domain specific config.
   */
  public function getVssCommonConfiguration(): array {
    $rawData = [];
    $rawData = $this->configFactory->get('vss_common_config.vsscommonconfig')->getRawData();
    return $rawData;
  }

  public function getFooterDetails(): array {
    $data = $this->checkConfiguration();
    $footerDetails = [];
    if (isset($data['vss_common_config'])) {
      $footerDetails['phone'] = $data['vss_common_config']['phone'];
      $footerDetails['email'] = $data['vss_common_config']['email'];
      $footerDetails['address'] = $data['vss_common_config']['address'];
    }
    return $footerDetails;
  }

  public function getDisclaimer(): array {
    $data = $this->checkConfiguration();
    $disclaimer = [];
    if (isset($data['vss_common_config'])) {
      $disclaimer['disclaimer_title'] = $data['vss_common_config']['disclaimer_title'];
      $disclaimer['disclaimer_description'] = $data['vss_common_config']['disclaimer_description']['value'];
      if (isset($data['vss_common_config']['disclaimer_image'])) {
        $file = File::load($data['vss_common_config']['disclaimer_image'][0]);
        $disclaimer['disclaimer_image'] = file_create_url($file->getFileUri());
      }
    }
    return $disclaimer;
  }

  protected function checkConfiguration() {
    $data = [];
    $data = $this->getVssDomainWithLanguageConfiguration();
    if (!empty($data)) {
      return $data;
    }
    $data = $this->getVssDomainWithoutLanguageConfiguration();
    if (!empty($data)) {
      return $data;
    }
    else {
      $data = $this->getVssCommonConfiguration();
    }
    return $data;
  }
}
