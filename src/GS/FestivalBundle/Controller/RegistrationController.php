<?php

namespace GS\FestivalBundle\Controller;

use GS\FestivalBundle\Entity\Registration;
use GS\FestivalBundle\Form\RegistrationType;
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
            $em = $this->getDoctrine()->getManager();
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

    public function viewAction($id, Request $request)
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

}
