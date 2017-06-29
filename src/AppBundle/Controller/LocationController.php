<?php
/**
 * Location controller.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Location;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Location controller.
 *
 * @Route("location")
 */
class LocationController extends BaseController
{
    /**
     * Lists all location entities.
     *
     *
     * @Route(
     *     "/",
     *     defaults={"page": 1},
     *     name="location_index",
     * )
     * @Route(
     *     "/page/{page}",
     *     requirements={"page": "[1-9]\d*"},
     *     name="location_index_paginated",
     * )
     * @Method("GET")
     * @param int $page Page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($page)
    {
        $repository = $this->get("app.repository.location");
        $locations = $repository->findAllPaginated($this->getUserProfile(), $page);


        return $this->render('location/index.html.twig', array(
            'locations' => $locations,
        ));
    }

    /**
     * Creates a new location entity.
     *
     * @Route("/new", name="location_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $location = new Location();
        $location->setProfile($this->getUserProfile());
        $form = $this->createForm('AppBundle\Form\LocationType', $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository = $this->get("app.repository.location");
            $repository->save($location);
            $this->addFlash('success', 'form.location_new.success');

            return $this->redirectToRoute('location_show', array('id' => $location->getId()));
        }

        return $this->render('location/new.html.twig', array(
            'location' => $location,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a location entity.
     *
     * @Route("/{id}", name="location_show")
     * @Method("GET")
     * @param Location $location
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Location $location)
    {
        $this->checkAccessPermission($location);
        $deleteForm = $this->createDeleteForm($location);

        return $this->render('location/show.html.twig', array(
            'location' => $location,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing location entity.
     *
     * @Route("/{id}/edit", name="location_edit")
     * @Method({"GET", "POST"})
     * @param Request  $request
     * @param Location $location
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Location $location)
    {
        $this->checkAccessPermission($location);
        $deleteForm = $this->createDeleteForm($location);
        $editForm = $this->createForm('AppBundle\Form\LocationType', $location);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $repository = $this->get("app.repository.location");
            $repository->save($location);
            $this->addFlash('success', 'form.location_edit.success');

            return $this->redirectToRoute('location_edit', array('id' => $location->getId()));
        }

        return $this->render('location/edit.html.twig', array(
            'location' => $location,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a location entity.
     *
     * @Route("/{id}", name="location_delete")
     * @Method("DELETE")
     * @param Request  $request
     * @param Location $location
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Location $location)
    {
        $this->checkAccessPermission($location);
        $form = $this->createDeleteForm($location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository = $this->get("app.repository.location");
            $repository->remove($location);
            $this->addFlash('success', 'form.location_delete.success');
        }

        return $this->redirectToRoute('location_index');
    }

    /**
     * Creates a form to delete a location entity.
     *
     * @param Location $location The location entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Location $location)
    {
        return $this->createFormBuilder(null, ['attr' => ['id' => 'delete_form']])
            ->setAction($this->generateUrl('location_delete', array('id' => $location->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
