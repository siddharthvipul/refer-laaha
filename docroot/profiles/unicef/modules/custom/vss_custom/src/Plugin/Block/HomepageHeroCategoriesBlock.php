<?php

namespace Drupal\vss_custom\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Cache\Cache;
use Drupal\taxonomy\Entity\Term;

/**
 * Provides a 'SocialIconsFooterBlock' block.
 *
 * @Block(
 *  id = "homepage_hero_categories_block",
 *  admin_label = @Translation("Homepage Hero categories block"),
 * )
 */
class HomepageHeroCategoriesBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal\vss_common_config\VssCommonInterface definition.
   *
   * @var \Drupal\vss_common_config\VssCommonInterface
   */
  protected $vssCommonConfigDefault;

  /**
   * Drupal\Core\Language\LanguageManagerInterface.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = new static($configuration, $plugin_id, $plugin_definition);
    $instance->vssCommonConfigDefault = $container->get('vss_common_config.default');
    $instance->languageManager = $container->get('language_manager');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $langcode = $this->languageManager->getCurrentLanguage()->getId();
    $content = $this->vssCommonConfigDefault->getCategories();
    foreach ($content['homepage_hero'] as $term_id) {
      $term_obj = Term::load($term_id);
      if ($term_obj) {
        $term_name = $term_obj->getName();
        $term_bgcolor = $term_obj->get('field_category_color')->getValue()[0]['color'];
        $icon_path = $term_obj->get('field_icon')->entity->getFileUri();
        $term_alias = $term_obj->get('path')->alias;
        $hero[] = [
          'id' => $term_id,
          'name' => $term_name,
          'color' => $term_bgcolor,
          'cat_uri' => $term_alias,
          'icon' => $icon_path,
        ];
      }
    }
    $build['#theme'] = 'homepage_hero_categories_block';
    $build['#content'] = $hero;
    $build['#lang_code'] = $langcode;
    $build['#cache']['tags'] = $this->getCacheTags();
    $build['#cache']['contexts'] = $this->getCacheContexts();

    return $build;
  }

  /**
   * Get cache tags.
   */
  public function getCacheTags() {
    return Cache::mergeTags(parent::getCacheTags(),
     ['config:vss_common_config.vsscommonconfig', 'vss_common_config']);
  }

  /**
   * Get cache Contexts.
   */
  public function getCacheContexts() {
    return Cache::mergeTags(parent::getCacheContexts(), ['url.path']);
  }

}