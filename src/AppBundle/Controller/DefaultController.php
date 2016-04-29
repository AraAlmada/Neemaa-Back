<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }

    /**
    *
    * @Route("/create", name="create_user")
    * @Method("POST")
    */
    public function createUserAction(Request $request)
    {


        $email      = $request->request->get('email');
        $password   = $request->request->get('password');

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

        $response = new Response(json_encode(array('response' => "ok")));
        $response->headers->set('Content-Type', 'application/json');
        return  $response;

    }


    private function checkUser($email)
    {
      $user = $this->get('fos_user.user_manager')->findUserByEmail($email);
      if ($user)
        return true;
      else
        return false;
    }


}
