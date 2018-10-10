<?php

namespace Drupal\node_json_expose\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Drupal\Core\Access\AccessResult;
use Drupal\node\Entity\Node;

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
  
  public function content($site_api_key, $id) {
    // Load save Site API Key
    $saved_site_api_key = \Drupal::config('system.site') -> get('siteapikey');
    
    // Initialize serializer service
    $serializer = \Drupal::service('serializer');
    
    // Initialize return data and update when required condition is met.
    $data = [
      'response_code' => 403,
      'data' => t('Access Denied'),
    ];

    if ($saved_site_api_key == $site_api_key) {
      // Load required node object
      $node_data = Node::load($id);

      if ($node_data) {
        // Check node content type
        $node_type = $node_data -> bundle();
        
        if ($node_type == 'page') {
          $data = [
            'response_code' => 200,
            'data' => $node_data,
          ];
        }
      }
    }
    
    // Converting data to JSON format Preparing response data
    $response_data = $serializer -> serialize($data, 'json');
    $response = new Response();
    $response -> setContent($response_data);
    return $response;
  }
}
