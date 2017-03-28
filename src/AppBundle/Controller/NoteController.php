<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use AppBundle\Entity\Event;
use AppBundle\Entity\Location;
use AppBundle\Entity\Note;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * @Route("/", name="note_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $notes = $em->getRepository('AppBundle:Note')->findAll();

        return $this->render('note/index.html.twig', array(
            'notes' => $notes,
        ));
    }

    /**
     * Add new note to event
     *
     * @Route("/event/new/{event}", name="note_add_to_event")
     * @Method({"GET", "POST"})
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

        $backRoute = $this->generateUrl('event_show', ['id'=>$event->getId()]);
        $response = $this->newNote($note, $form, $request, $backRoute);
        return $response;
    }

    /**
     * Add new note to contact
     *
     * @Route("/contact/new/{contact}", name="note_add_to_contact")
     * @Method({"GET", "POST"})
     */
    public function addNoteToContactAction(Request $request, Contact $contact)
    {
        $note = new Note();
        $note->setContact($contact);
        $form = $this->createForm('AppBundle\Form\NoteType', $note, [
            'note_type' => Note::CONTACT_TYPE,
            'owner' => $this->getUserProfile(),
            'chose_parent_entity' => false
        ]);

        $backRoute = $this->generateUrl('contact_show', ['id'=>$contact->getId()]);
        $response = $this->newNote($note, $form, $request, $backRoute);
        return $response;
    }

    /**
     * Add new note to location
     *
     * @Route("/location/new/{location}", name="note_add_to_location")
     * @Method({"GET", "POST"})
     */
    public function addNoteToLocationAction(Request $request, Location $location)
    {
        $note = new Note();
        $note->setLocation($location);
        $form = $this->createForm('AppBundle\Form\NoteType', $note, [
            'note_type' => Note::LOCATION_TYPE,
            'owner' => $this->getUserProfile(),
            'chose_parent_entity' => false
        ]);
        $backRoute = $this->generateUrl('note_show', ['id'=>$note->getId()]);
        $response = $this->newNote($note, $form, $request, $backRoute);
        return $response;
    }

    /**
     * Creates a new event note
     *
     * @Route("/event/new", name="event_note_new")
     * @Method({"GET", "POST"})
     */
    public function newEventNoteAction(Request $request)
    {
        $note = new Note();
        $note->setType(Note::EVENT_TYPE);
        $form = $this->createForm('AppBundle\Form\NoteType', $note, [
            'note_type' => Note::EVENT_TYPE,
            'owner' => $this->getUserProfile()
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
     */
    public function newLocationNoteAction(Request $request)
    {
        $note = new Note();
        $note->setType(Note::LOCATION_TYPE);
        $form = $this->createForm('AppBundle\Form\NoteType', $note, [
            'note_type' => Note::LOCATION_TYPE,
            'owner' => $this->getUserProfile()
        ]);

        $backRoute = $this->generateUrl('note_index');
        $response = $this->newNote($note, $form, $request, $backRoute);
        return $response;
    }

    /**
     * @param Note $note
     * @param Form $form
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
   private function newNote(Note $note, Form $form, Request $request, $backRoute){
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
           'backRoute' =>$backRoute
       ));
   }

    /**
     * Finds and displays a note entity.
     *
     * @Route("/{id}", name="note_show")
     * @Method("GET")
     */
    public function showAction(Note $note)
    {
        $deleteForm = $this->createDeleteForm($note);

        return $this->render('note/show.html.twig', array(
            'note' => $note,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing note entity.
     *
     * @Route("/{id}/edit", name="note_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Note $note)
    {
        $deleteForm = $this->createDeleteForm($note);
        $editForm = $this->createForm('AppBundle\Form\NoteType', $note, [
            'note_type' => $note->getType(),
            'owner' => $this->getUserProfile()
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
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('note_delete', array('id' => $note->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
