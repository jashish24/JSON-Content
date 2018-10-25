<?php

namespace Drupal\node_json_expose\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;
use Drupal\node\NodeInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;

/*
 * Controller to deliver node in JSON format
 */

class DeliverJson extends ControllerBase {
  /**
   * Function to generate node JSON content
   *
   * @param string $site_api_key The passed Site API Key
   * @param number $id The id of the node that needs to be delivered in JSON format
   * @return JSON formatted response with code
   * 
   */
  
  protected $response;
  protected $serializer;
  protected $config_factory;
  
  public function __construct(Serializer $serializer, ConfigFactoryInterface $config_factory) {
    // Initialize serialize service
    $this -> serializer = $serializer;
    
    // Initialize response
    $this -> response = new Response();
    
    // Initialize Response
    $this -> config_factory = $config_factory;
  }
  
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // Instantiates this form class.
    return new static(
      // Load the service required to construct this class.
      $container -> get('serializer'),
      $container -> get('config.factory')
    );
  }
  
  public function content($site_api_key, NodeInterface $node) {
    // Load save Site API Key
    $saved_site_api_key = $this -> config_factory -> get('system.site') -> get('siteapikey');

    if ($saved_site_api_key == $site_api_key) {
      // Check node content type
      $node_type = $node -> bundle();
      
      if ($node_type == 'page') {
        // Converting data to JSON format Preparing response data
        $response_data = $this -> serializer -> serialize($node, 'json');
        $this -> response -> setStatusCode(200);
        $this -> response -> setContent($response_data);
      }
    }
    else {
      $response_data = t('Access Denied');
      $this -> response -> setStatusCode(403);
      $this -> response -> setContent($response_data);
    }
    
    return $this -> response;
  }
}
