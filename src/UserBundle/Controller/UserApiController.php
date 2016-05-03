<?php

namespace UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
*@Route("/api/user")
*/
class UserApiController extends Controller
{
  /**
  * @Route("/update/profile", name="api_update_user_profile")
  * @Method("POST")
  */
  public function updateUserAction(Request $request)
  {

    $firstName  = $request->request->get('firstName');
    $lastName  = $request->request->get('lastName');
    $email  = $request->request->get('email');
    $sex  = $request->request->get('sex');
    $codePostal  = $request->request->get('codePostal');
    $birthdate  = $request->request->get('birthdate');
    $skinType  = $request->request->get('skinType');
    $skinColor  = $request->request->get('skinColor');
    $hairType  = $request->request->get('hairType');
    $hairColor  = $request->request->get('hairColor');

    $lexik_manager = $this->container->get('lexik_jwt_authentication.jwt_manager');
    var_dump($lexik_manager);
    die;
    $response = new Response(json_encode(array('response' => "OK")));
    $response->headers->set('Content-Type', 'application/json');
    return  $response;
  }
}
