<?php

namespace UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
*@Route("/user")
*/
class UserController extends Controller
{

  /**
  *
  * @Route("/create", name="create_user")
  * @Method("POST")
  */
  public function createUserAction(Request $request)
  {

      $email      = $request->request->get('email');
      $password   = $request->request->get('password');


      if( ! $this->isValidPassword($password) || ! $this->isValidEmail($email) ){
        $response = new Response(json_encode(array('response' => "NOK")));
        $response->headers->set('Content-Type', 'application/json');
        return  $response;
      }

      if ($this->checkUser($email)) {

        $response = new Response(json_encode(array('response' => "already_exist")));
        $response->headers->set('Content-Type', 'application/json');
        return  $response;
      }

      $user_manager = $this->container->get('fos_user.user_manager');

      $user = $user_manager->createUser();
      $user->setUsername($email);
      $user->setEmail($email);
      $user->setPlainPassword($password);
      $user->setEnabled(true);
      $user->addRole('ROLE_USER');

      $user_manager->updateUser($user);

      $lexik_manager = $this->container->get('lexik_jwt_authentication.jwt_manager');
      $token = $lexik_manager->create($user) ;
      $response = new Response(json_encode(array('response' => "done",'data'=>  array('token' => $token ))));
      $response->headers->set('Content-Type', 'application/json');
      return  $response;

  }

  /**
   * @param string     $email
   */
  private function checkUser($email)
  {
    $user = $this->get('fos_user.user_manager')->findUserByEmail($email);
    if ($user)
      return true;
    else
      return false;
  }

  /**
   * @param string     $password
   */
  private function isValidPassword($password){

      if ( isset($password) && !is_null($password) && is_string($password) )
          if (strlen($password) > 5 )
            return true;
          else
            return false;
      else
          return false;
  }

  /**
   * @param string     $email
   */
  private function isValidEmail($email){

    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
  }

}
