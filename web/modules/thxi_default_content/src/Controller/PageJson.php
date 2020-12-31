<?php

namespace Drupal\thxi_default_content\Controller;


use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;

class PageJson extends ControllerBase {

    /**
       * Get json format of Page Node.
       *
       * @return mixed
       */
    public function checkPage() {
        $output = array(
            'status' => false,
            'data' => '',
          );
          $path = \Drupal::request()->getpathInfo();
          $arg  = explode('/',$path);
          $key = '';
          // Check if site api key matches
          if( false == empty( $arg[2] ) ) {
            $key = $arg[2];
            $siteapikey = \Drupal::config('system.site')->get('siteapikey');
            if ( $key != $siteapikey ) {
              //Access denied
              $output['data'] = 'Access Denied';
              return new JsonResponse($output);
            }
          }
          $nid = $arg[3];
          $node = Node::load($nid);
          // Check if node exist
          if( true == empty( $node ) ) {
            $output['data'] = 'Node does not exist.';
            $output['status'] = false;
          } else {
            // Use of serializer to get json encoding
            $serializer = \Drupal::service('serializer');
            $data = $serializer->serialize($node, 'json', ['plugin_id' => 'entity']);
            $output['data'] = json_decode($data);
            $output['status'] = true;
          }
          return new JsonResponse($output);
    }
    
}