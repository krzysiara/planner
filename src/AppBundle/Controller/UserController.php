<?php
/**
 * User controller.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\NullOutput;

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

    /**
     * Promote user
     * @Route("/promote/{user}", name="user_promote")
     * @Method("POST")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Throwable
     */
    public function promoteUser(User $user){
        $kernel = $this->get('kernel');
        $application = new Application($kernel);

        $command = $application->find('fos:user:promote');
        $arguments = array(
            'command' => 'fos:user:promote',
            'username'    => $user->getUsername(),
            'role'  => 'ROLE_ADMIN',
        );

        $output = new NullOutput();
        $input = new ArrayInput($arguments);
        $command->run($input, $output);
        $this->addFlash("success", 'user promoted');

        return $this->redirectToRoute('user_index');
    }

    /**
     * Promote user
     * @Route("/demote/{user}", name="user_demote")
     * @Method("POST")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Throwable
     */
    public function demoteUser(User $user){
        if($user!=$this->getUser()){
            $kernel = $this->get('kernel');
            $application = new Application($kernel);

            $command = $application->find('fos:user:demote');
            $arguments = array(
                'command' => 'fos:user:demote',
                'username'    => $user->getUsername(),
                'role'  => 'ROLE_ADMIN',
            );

            $output = new NullOutput();
            $input = new ArrayInput($arguments);
            $command->run($input, $output);
            $this->addFlash("success", 'user demoted');
        }else{
            $this->addFlash("danger", "Nie możesz zmienić swojej roli!");
        }

        return $this->redirectToRoute('user_index');
    }



}
