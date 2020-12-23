<?php
namespace Drupal\thxi_default_content\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \GuzzleHttp\Exception\RequestException;
use Drupal\node\NodeInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

class PageJson extends ControllerBase {

    /**
       * Get configuration or state setting for this Fpx integration module.
       *
       * @param string $name this module's config or state.
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
          if( false == empty( $arg[2] ) ) {
            $key = $arg[2];
            $siteapikey = \Drupal::config('system.site')->get('siteapikey');
            if ( $key != $siteapikey ) {
              //access denied
              $output['data'] = 'Access Denied';
              return new JsonResponse($output);
            }
          }
          $nid = $arg[3];
          $node = Node::load($nid);
          if( true == empty( $node ) ) {
            $output['data'] = 'Node does not exist.';
            $output['status'] = false;
          } else {
            $serializer = \Drupal::service('serializer');
            $data = $serializer->serialize($node, 'json', ['plugin_id' => 'entity']);
            $output['data'] = json_decode($data);
            $output['status'] = true;
          }
          return new JsonResponse($output);
    }
    
}