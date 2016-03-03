<?php

namespace GS\FestivalBundle\Controller;

use GS\FestivalBundle\Entity\Registration;
use GS\FestivalBundle\Form\RegistrationType;
use GS\FestivalBundle\Form\RegistrationEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RegistrationController extends Controller
{

    public function addAction($id, Request $request)
    {
        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();

        // Pour récupérer une annonce unique : on utilise find()
        $festival = $em->getRepository('GSFestivalBundle:Festival')->find($id);
        if ($festival === null) {
            throw $this->createNotFoundException("Le festival d'id " . $id . " n'existe pas.");
        }

        $registration = new Registration();
        $form = $this->createForm(RegistrationType::class, $registration, array('festival' => $festival));

        if ($form->handleRequest($request)->isValid()) {
            $registration->setRemainingPayment($form->get('level')->getData()->getPrice());
            $em->persist($registration);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Inscription bien enregistrée.');

            return $this->redirectToRoute('gs_registration_view', array('id' => $registration->getId()));
        }

        return $this->render('GSFestivalBundle:Registration:add.html.twig', array(
                    'festival' => $festival,
                    'form' => $form->createView(),
        ));
    }

    public function viewAction($id)
    {
        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();

        $registration = $em->getRepository('GSFestivalBundle:Registration')->find($id);
        if ($registration === null) {
            throw $this->createNotFoundException("L'inscription d'id " . $id . " n'existe pas.");
        }

        return $this->render('GSFestivalBundle:Registration:view.html.twig', array(
                    'registration' => $registration,
        ));
    }

    public function editAction($id, Request $request)
    {
        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();

        $registration = $em->getRepository('GSFestivalBundle:Registration')->find($id);
        if ($registration === null) {
            throw $this->createNotFoundException("L'inscription d'id " . $id . " n'existe pas.");
        }

        $festival = $registration->getLevel()->getFestival();
        $form = $this->createForm(RegistrationEditType::class, $registration, array('festival' => $festival));

        if ($form->handleRequest($request)->isValid()) {
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Inscription bien modifiée.');

            return $this->redirectToRoute('gs_level_view', array(
                        'id' => $registration->getLevel()->getId(),
            ));
        }

        return $this->render('GSFestivalBundle:Registration:edit.html.twig', array(
                    'festival' => $festival,
                    'form' => $form->createView(),
                    'previousUrl' => $this->generateUrl('gs_level_view', array(
                        'id' => $registration->getLevel()->getId(),
                    )),
        ));
    }

    public function validateAction($id, Request $request)
    {
        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();

        $registration = $em->getRepository('GSFestivalBundle:Registration')->find($id);
        if ($registration === null) {
            throw $this->createNotFoundException("L'inscription d'id " . $id . " n'existe pas.");
        }

        if ($registration->getStatus() == 'received' || $registration->getStatus() == 'waiting') {
            $registration->setStatus('validated');
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Inscription validée.');
        } else {
            $request->getSession()->getFlashBag()->add('warning', 'Impossible de valider l\'inscription, vérifiez son status.');
        }

        return $this->redirectToRoute('gs_level_view', array(
                    'id' => $registration->getLevel()->getId(),
        ));
    }

    public function waitingAction($id, Request $request)
    {
        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();

        $registration = $em->getRepository('GSFestivalBundle:Registration')->find($id);
        if ($registration === null) {
            throw $this->createNotFoundException("L'inscription d'id " . $id . " n'existe pas.");
        }

        $done = false;
        if ($registration->getStatus() == 'received') {
            $registration->setStatus('waiting');
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Inscription mise en liste d\'attente.');
            $done = true;
        } else if ($registration->getStatus() != 'validated') {
            $request->getSession()->getFlashBag()->add('warning', 'Impossible de valider l\'inscription, vérifiez son status.');
            $done = true;
        }
        
        if ($done) {
            return $this->redirectToRoute('gs_level_view', array(
                        'id' => $registration->getLevel()->getId(),
            ));
        }

        $form = $this->createFormBuilder()->getForm();
        if ($form->handleRequest($request)->isValid()) {
            $registration->setStatus('waiting');
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Inscription mise en liste d\'attente.');

            return $this->redirectToRoute('gs_level_view', array(
                        'id' => $registration->getLevel()->getId(),
            ));
        }

        // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
        return $this->render('GSFestivalBundle:Registration:waiting.html.twig', array(
                    'form' => $form->createView(),
                    'registration' => $registration,
        ));
    }

    public function deleteAction($id, Request $request)
    {
        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();

        // On récupère l'entité correspondant à l'id $id
        $registration = $em->getRepository('GSFestivalBundle:Registration')->find($id);

        // Si l'annonce n'existe pas, on affiche une erreur 404
        if ($registration == null) {
            throw $this->createNotFoundException("Le niveau d'id " . $id . " n'existe pas.");
        }

        $form = $this->createFormBuilder()->getForm();
        $level = $registration->getLevel();

        if ($form->handleRequest($request)->isValid()) {
            $level->removeRegistration($registration);
            $em->remove($registration);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', "L'inscription a bien été supprimée.");

            return $this->redirect($this->generateUrl('gs_level_view', array('id' => $level->getId())));
        }

        // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
        return $this->render('GSFestivalBundle:Registration:delete.html.twig', array(
                    'form' => $form->createView(),
                    'registration' => $registration,
        ));
    }

}
