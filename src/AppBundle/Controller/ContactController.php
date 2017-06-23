<?php
/**
 * Contact controller.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Contact controller.
 *
 * @Route("contact")
 */
class ContactController extends BaseController
{
    /**
     * Lists all contact entities.
     *
     * @Route(
     *     "/",
     *     defaults={"page": 1},
     *     name="contact_index",
     * )
     * @Route(
     *     "/page/{page}",
     *     requirements={"page": "[1-9]\d*"},
     *     name="contact_index_paginated",
     * )
     * @Method("GET")
     * @param int $page Page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getManager();

        $contacts = $em->getRepository('AppBundle:Contact')->findAllPaginated($this->getUserProfile(), $page);

        return $this->render('contact/index.html.twig', array(
            'contacts' => $contacts,
        ));
    }

    /**
     * Creates a new contact entity.
     *
     * @Route("/new", name="contact_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $contact = new Contact();
        $ownerProfile = $this->getUserProfile();
        $contact->setProfile($ownerProfile);
        $form = $this->createForm('AppBundle\Form\ContactType', $contact, ['owner_profile' => $ownerProfile, ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();

            $this->addFlash('success', 'form.contact_new.success');

            return $this->redirectToRoute('contact_show', array('id' => $contact->getId()));
        }

        return $this->render('contact/new.html.twig', array(
            'contact' => $contact,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a contact entity.
     *
     * @Route("/{id}", name="contact_show")
     * @Method("GET")
     * @param Contact $contact
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Contact $contact)
    {
        $deleteForm = $this->createDeleteForm($contact);

        return $this->render('contact/show.html.twig', array(
            'contact' => $contact,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing contact entity.
     *
     * @Route("/{id}/edit", name="contact_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Contact $contact
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Contact $contact)
    {
        $deleteForm = $this->createDeleteForm($contact);
        $editForm = $this->createForm('AppBundle\Form\ContactType', $contact, ['owner_profile' => $this->getUserProfile()]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();
            $this->addFlash('success', 'form.contact_edit.success');

            return $this->redirectToRoute('contact_edit', array('id' => $contact->getId()));
        }

        return $this->render('contact/edit.html.twig', array(
            'contact' => $contact,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a contact entity.
     *
     * @Route("/{id}", name="contact_delete")
     * @Method("DELETE")
     * @param Request $request
     * @param Contact $contact
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Contact $contact)
    {
        $form = $this->createDeleteForm($contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $this->addFlash('success', 'form.contact_delete.success');
            $em->remove($contact);
            $em->flush();
        }

        return $this->redirectToRoute('contact_index');
    }

    /**
     * Creates a form to delete a contact entity.
     *
     * @param Contact $contact The contact entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Contact $contact)
    {
        return $this->createFormBuilder(null, ['attr' => ['id' => 'delete_form']])
            ->setAction($this->generateUrl('contact_delete', array('id' => $contact->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
