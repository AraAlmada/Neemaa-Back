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
      $user->setEnabled(false);
      $user->setPartner(false);

      $tokenGenerator = $this->container->get('fos_user.util.token_generator');
      $user->setConfirmationToken($tokenGenerator->generateToken());

      $user_manager->updateUser($user);



      return  $this->forward('UserBundle:User:remind', array(
        'email'  => $email,
    ));

  }

  /**
  *
  * @Route("/get/token", name="get_token")
  * @Method("POST")
  */
  public function getTokenAction(Request $request)
  {
     $email  = $request->request->get('email');
     $password  = $request->request->get('password');

    if(  ! $this->isValidEmail($email) ){
      $response = new Response(json_encode(array('response' => "NOK")));
      $response->headers->set('Content-Type', 'application/json');
      return  $response;
    }

   if ( ! $this->checkUser($email)) {
      $response = new Response(json_encode(array('response' => "user_does_not_exist")));
      $response->headers->set('Content-Type', 'application/json');
      return  $response;
    }

      $user = $this->get('fos_user.user_manager')->findUserByEmail($email);

      $encoder_service = $this->get('security.encoder_factory');
      $encoder = $encoder_service->getEncoder($user);
      $encoded_pass = $encoder->encodePassword($password, $user->getSalt());

      if ( $user->getPassword() != $encoded_pass) {
        $response = new Response(json_encode(array('response' => "wrong_pass")));
        $response->headers->set('Content-Type', 'application/json');
        return  $response;
      }
      if (! $user->isEnabled()) {
        $response = new Response(json_encode(array('response' => "NOT_ENABLED")));
        $response->headers->set('Content-Type', 'application/json');
        return  $response;
      }
      $lexik_manager = $this->container->get('lexik_jwt_authentication.jwt_manager');
      $token = $lexik_manager->create($user) ;
      $response = new Response(json_encode(array('response' => "OK",'data'=>  array('token' => $token ))));
      $response->headers->set('Content-Type', 'application/json');
      return  $response;


  }


  /**
   * resend confirmation email
   * @Route("/send/{email}")
   * @Method("POST")
   */
  public function remindAction($email)
  {
    if ( ! $this->checkUser($email)) {
       $response = new Response(json_encode(array('response' => "user_does_not_exist")));
       $response->headers->set('Content-Type', 'application/json');
       return  $response;
     }

      $user = $this->get('fos_user.user_manager')->findUserByEmail($email);
      $url = $this->generateUrl('fos_user_registration_confirm', array('token' => $user->getConfirmationToken()), true);

      $message = \Swift_Message::newInstance()
              ->setSubject('Registration confirmation')
              ->setFrom('neemaa.test@gmail.com')
              ->setTo($email)
              ->setContentType('text/html')
              ->setBody(
              $this->renderView(
                      "UserBundle:Registration:confirmationEmail.html.twig", array(
                  'user' => $user,
                  'confirmationUrl' => $url))
              );
      $sent = $this->get('mailer')->send($message);

      $response = new Response(json_encode(array('response' => "OK",'data'=>  array('sent' => $sent ))));
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
