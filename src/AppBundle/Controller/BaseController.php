<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use AppBundle\Entity\Event;
use AppBundle\Entity\Location;
use AppBundle\Entity\Profile;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class BaseController extends Controller
{

    /**
     * @return Profile
     */
    public function getUserProfile()
    {
        return $this->getUser()->getProfile();
    }

    /**
     * @param Contact | Location | Event $object
     * @return bool
     */
    public function checkAccessPermission($object){
        if($this->isGranted('ROLE_SUPERADMIN')){
            return true;
        }else{
            if($object->getProfile()!=$this->getUserProfile()){
                throw new AccessDeniedException();
            }
        }
        return true;
    }
}
