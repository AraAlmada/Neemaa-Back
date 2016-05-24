<?php

namespace UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\SerializerBuilder;
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

    $firstName  = $request->request->get('first_name');
    $lastName  = $request->request->get('last_name');
    $sex  = $request->request->get('sex');
    $codePostal  = $request->request->get('code_postal');
    $birthdate  = $request->request->get('birthdate');
    $skinType  = $request->request->get('skin_type');
    $skinColor  = $request->request->get('skin_color');
    $hairType  = $request->request->get('hair_type');
    $hairColor  = $request->request->get('hair_color');
    $phone  = $request->request->get('phone');
    $address  = $request->request->get('address');
    $partner  = $request->request->get('partner');

    $user = $this->get('security.token_storage')->getToken()->getUser();

    $user->setFirstName($firstName);
    $user->setLastName($lastName);
    $user->setSex($sex);
    $user->setCodePostal($codePostal);
    try {
      if ($this->validateDateFormat($birthdate)) {
          $birthdate = new \DateTime($birthdate);
          $user->setBirthdate($birthdate);
      }

    } catch (Exception $e) {

    }

    $user->setSkinType($skinType);
    $user->setSkinColor($skinColor);
    $user->setHairType($hairType);
    $user->setHairColor($hairColor);
    $user->setPhone($phone);
    $user->setAddress($address);
    $user->setPartner((bool)$partner);



    $user_manager = $this->container->get('fos_user.user_manager');
    $user_manager->updateUser($user);

    $serializer = $this->get('jms_serializer');
    $jsonContent = $serializer->serialize($user, 'json');

    $response = new Response(json_encode(array('response' => 'OK', 'data'=> $jsonContent)));
    $response->headers->set('Content-Type', 'application/json');
    return  $response;
  }

  /**
  * @Route("/get/profile", name="api_get_user")
  * @Method("POST")
  */
  public function getUserAction()
  {
    $user = $this->get('security.token_storage')->getToken()->getUser();

    $serializer = $this->get('jms_serializer');
    $jsonContent = $serializer->serialize($user, 'json');

    $response = new Response(json_encode(array('response' => 'OK', 'data'=> $jsonContent)));
    $response->headers->set('Content-Type', 'application/json');
    return  $response;
  }

  private function validateDateFormat($date)
  {
    if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date))
    {
        return true;
    }else{
        return false;
    }
  }
}
