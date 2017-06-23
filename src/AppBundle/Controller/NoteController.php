<?php
/**
 * Note controller.
 */
namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use AppBundle\Entity\Event;
use AppBundle\Entity\Location;
use AppBundle\Entity\Note;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * Note controller.
 *
 * @Route("note")
 */
class NoteController extends BaseController
{
    /**
     * Lists all note entities.
     *
     * @Route(
     *     "/",
     *     defaults={"page": 1},
     *     name="note_index",
     * )
     * @Route(
     *     "/page/{page}",
     *     requirements={"page": "[1-9]\d*"},
     *     name="note_index_paginated",
     * )
     * @Method("GET")
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getManager();
        $notes = $em->getRepository('AppBundle:Note')->findAllPaginated($this->getUserProfile(), $page);

        return $this->render('note/index.html.twig', array(
            'notes' => $notes,
        ));
    }

    /**
     * Add new note to event
     *
     * @Route("/event/new/{event}", name="note_add_to_event")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Event   $event
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addNoteToEventAction(Request $request, Event $event)
    {
        $note = new Note();
        $note->setEvent($event);
        $form = $this->createForm('AppBundle\Form\NoteType', $note, [
            'note_type' => Note::EVENT_TYPE,
            'owner' => $this->getUserProfile(),
            'chose_parent_entity' => false,
        ]);

        $backRoute = $this->generateUrl('event_show', ['id' => $event->getId(), ]);
        $response = $this->newNote($note, $form, $request, $backRoute);

        return $response;
    }

    /**
     * Add new note to contact
     *
     * @Route("/contact/new/{contact}", name="note_add_to_contact")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Contact $contact
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addNoteToContactAction(Request $request, Contact $contact)
    {
        $note = new Note();
        $note->setContact($contact);
        $form = $this->createForm('AppBundle\Form\NoteType', $note, [
            'note_type' => Note::CONTACT_TYPE,
            'owner' => $this->getUserProfile(),
            'chose_parent_entity' => false,
        ]);

        $backRoute = $this->generateUrl('contact_show', ['id' => $contact->getId()]);
        $response = $this->newNote($note, $form, $request, $backRoute);

        return $response;
    }

    /**
     * Add new note to location
     *
     * @Route("/location/new/{location}", name="note_add_to_location")
     * @Method({"GET", "POST"})
     * @param Request  $request
     * @param Location $location
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addNoteToLocationAction(Request $request, Location $location)
    {
        $note = new Note();
        $note->setLocation($location);
        $form = $this->createForm('AppBundle\Form\NoteType', $note, [
            'note_type' => Note::LOCATION_TYPE,
            'owner' => $this->getUserProfile(),
            'chose_parent_entity' => false,
        ]);

        $backRoute = $this->generateUrl('location_show', ['id' => $location->getId()]);
        $response = $this->newNote($note, $form, $request, $backRoute);

        return $response;
    }

    /**
     * Creates a new event note
     *
     * @Route("/event/new", name="event_note_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newEventNoteAction(Request $request)
    {
        $note = new Note();
        $note->setType(Note::EVENT_TYPE);
        $form = $this->createForm('AppBundle\Form\NoteType', $note, [
            'note_type' => Note::EVENT_TYPE,
            'owner' => $this->getUserProfile(),
        ]);

        $backRoute = $this->generateUrl('note_index');
        $response = $this->newNote($note, $form, $request, $backRoute);

        return $response;
    }

    /**
     * Creates a new contact note
     *
     * @Route("/contact/new", name="contact_note_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newContactNoteAction(Request $request)
    {
        $note = new Note();
        $note->setType(Note::CONTACT_TYPE);
        $form = $this->createForm('AppBundle\Form\NoteType', $note, [
            'note_type' => Note::CONTACT_TYPE,
            'owner' => $this->getUserProfile(),
        ]);

        $backRoute = $this->generateUrl('note_index');
        $response = $this->newNote($note, $form, $request, $backRoute);

        return $response;
    }

    /**
     * Creates a new location note
     *
     * @Route("/location/new", name="location_note_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newLocationNoteAction(Request $request)
    {
        $note = new Note();
        $note->setType(Note::LOCATION_TYPE);
        $form = $this->createForm('AppBundle\Form\NoteType', $note, [
            'note_type' => Note::LOCATION_TYPE,
            'owner' => $this->getUserProfile(),
        ]);

        $backRoute = $this->generateUrl('note_index');
        $response = $this->newNote($note, $form, $request, $backRoute);

        return $response;
    }


    /**
     * Displays a form to edit an existing note entity.
     *
     * @Route("/{id}/edit", name="note_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Note    $note
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Note $note)
    {
        $deleteForm = $this->createDeleteForm($note);
        $editForm = $this->createForm('AppBundle\Form\NoteType', $note, [
            'note_type' => $note->getType(),
            'owner' => $this->getUserProfile(),
        ]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('note_edit', array('id' => $note->getId()));
        }

        return $this->render('note/edit.html.twig', array(
            'note' => $note,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a note entity.
     *
     * @Route("/{id}", name="note_delete")
     * @Method("DELETE")
     * @param Request $request
     * @param Note    $note
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Note $note)
    {
        $form = $this->createDeleteForm($note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($note);
            $em->flush();
        }

        return $this->redirectToRoute('note_index');
    }

    /**
     * Creates a form to delete a note entity.
     *
     * @param Note $note The note entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Note $note)
    {
        return $this->createFormBuilder(null, ['attr' => ['id' => 'delete_form']])
            ->setAction($this->generateUrl('note_delete', array('id' => $note->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


    /**
     * new Note
     * @param Note $note
     * @param Form $form
     * @param Request $request
     * @param $backRoute
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    private function newNote(Note $note, Form $form, Request $request, $backRoute)
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($note);
            $em->flush();

            return $this->redirectToRoute('note_show', array('id' => $note->getId()));
        }

        return $this->render('note/new.html.twig', array(
            'note' => $note,
            'form' => $form->createView(),
            'backRoute' => $backRoute,
        ));
    }
}
