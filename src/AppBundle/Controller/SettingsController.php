<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Settings;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Settings controller.
 *
 * @Route("settings")
 */
class SettingsController extends BaseController
{

    /**
     * Finds and displays a contact entity.
     *
     * @Route("/", name="settings_show")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction()
    {
        $settings = $this->getUser()->getSettings();
        return $this->render('settings/show.html.twig', array(
            'settings' => $settings,
        ));
    }

    /**
     * Displays a form to edit an existing settings entity.
     *
     * @Route("/edit", name="settings_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request)
    {
        /** @var Settings $settings */
        $settings = $this->getUser()->getSettings();
        $editForm = $this->createForm('AppBundle\Form\SettingsType', $settings);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($settings);
            $em->flush();
            $this->addFlash('success', 'form.settings_edit.success');
            return $this->redirectToRoute('settings_edit', array('id' => $settings->getId()));
        }

        return $this->render('settings/edit.html.twig', array(
            'settings' => $settings,
            'edit_form' => $editForm->createView(),
        ));
    }
}
