<?php
/**
 * UserProfileController
 */
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\BrowserKit\Request;

/**
 * UserProfile controller.
 *
 * @Route("profile")
 */
class UserProfileController extends BaseController
{

    /**
     * Edit the profile
     * @Route("/profile_edit", name="edit_user_profile")
     * @Method("GET")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(\Symfony\Component\HttpFoundation\Request $request)
    {
        $profile = $this->getUserProfile();
        $editForm = $this->createForm('AppBundle\Form\ProfileType', $profile);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'form.profile_edit.success');

            return $this->redirectToRoute('fos_user_profile_show');
        }

        return $this->render('profile/edit.html.twig', array(
            'edit_form' => $editForm->createView(),
        ));
    }
}
