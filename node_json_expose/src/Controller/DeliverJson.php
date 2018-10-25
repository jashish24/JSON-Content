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
  protected $response;
  protected $serializer;
  protected $config_factory;
  protected $site_api_key;
  
  public function __construct(Serializer $serializer, ConfigFactoryInterface $config_factory) {
    // Initialize serialize service
    $this -> serializer = $serializer;
    
    // Set JSON header
    $headers = [
      'Content-Type' => 'application/json',
    ];
    
    // Set default data
    $response_data = $this -> serializer -> serialize('Access Denied', 'json');
    
    // Initialize response
    $this -> response = new Response($response_data, 403, $headers);
    
    // Initialize Configuration service
    $this -> config_factory = $config_factory;
    
    // Load saved Site API Key
    $this -> site_api_key = $this -> config_factory -> get('system.site') -> get('siteapikey');
  }
  
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      // Load the service required to construct this class.
      $container -> get('serializer'),
      $container -> get('config.factory')
    );
  }
  
  /**
   * Function to generate node JSON content
   *
   * @param string $site_api_key The passed Site API Key
   * @param number $node Node object that is passed in URL
   * @return JSON formatted response with code
   * 
   */
  
  public function content($site_api_key, NodeInterface $node) {    
    if ($this -> site_api_key == $site_api_key) {
      // Check node content type
      $node_type = $node -> bundle();
      
      if ($node_type == 'page') {
        // Converting data to JSON format Preparing response data
        $response_data = $this -> serializer -> serialize($node, 'json');
        $this -> response -> setStatusCode(200);
        $this -> response -> setContent($response_data);
      }
    }
    
    return $this -> response;
  }
}
