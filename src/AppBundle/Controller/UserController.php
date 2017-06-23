<?php
/**
 * User controller.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * User controller.
 *
 * @Route("user")
 */
class UserController extends BaseController
{

    /**
     * Index
     * @Route(
     *     "/",
     *     defaults={"page": 1},
     *     name="user_index",
     * )
     * @Route(
     *     "/page/{page}",
     *     requirements={"page": "[1-9]\d*"},
     *     name="user_index_paginated",
     * )
     * @Method("GET")
     * @param int $page Page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($page)
    {
        $users = $this->get("app.repository.user")->findAllPaginated($page);

        return $this->render('user/index.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * Index
     * @Route("/disable/{user}", name="user_disable")
     * @Method("POST")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function disableAction(User $user)
    {
        $user->setEnabled(false);
        $this->get("app.repository.user")->save($user);
        $this->addFlash("success", 'User disabled');

        return $this->redirectToRoute('user_index');
    }

    /**
     * Index
     * @Route("/enable/{user}", name="user_enable")
     * @Method("POST")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function enableAction(User $user)
    {
        $user->setEnabled(true);
        $this->get("app.repository.user")->save($user);
        $this->addFlash("success", 'User enabled');

        return $this->redirectToRoute('user_index');
    }
}
