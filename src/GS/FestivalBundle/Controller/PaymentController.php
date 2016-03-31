<?php

namespace GS\FestivalBundle\Controller;

use GS\FestivalBundle\Entity\Payment;
use GS\FestivalBundle\Form\PaymentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PaymentController extends Controller
{

    public function addAction($id, Request $request)
    {
        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();

        $registration = $em->getRepository('GSFestivalBundle:Registration')->find($id);
        if ($registration === null) {
            throw $this->createNotFoundException("L'inscription d'id " . $id . " n'existe pas.");
        }

        $payment = new Payment();
        $payment->setAmount($registration->getRemainingPayment());
        $form = $this->createForm(PaymentType::class, $payment);

        if ($form->handleRequest($request)->isValid()) {
            $registration->addPayment($payment);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Paiement ajouté.');

            return $this->redirectToRoute('gs_level_view', array('id' => $registration->getLevel()->getId()));
        }

        return $this->render('GSFestivalBundle:Payment:add.html.twig', array(
                    'form' => $form->createView(),
                    'levelId' => $registration->getLevel()->getId(),
        ));
    }

//    public function editAction($id, Request $request)
//    {
//        // On récupère l'EntityManager
//        $em = $this->getDoctrine()->getManager();
//
//        // On récupère l'entité correspondant à l'id $id
//        $level = $em->getRepository('GSFestivalBundle:Level')->find($id);
//        if ($level === null) {
//            throw $this->createNotFoundException("Le niveau d'id " . $id . " n'existe pas.");
//        }
//
//        $form = $this->createForm(LevelType::class, $level);
//
//        if ($form->handleRequest($request)->isValid()) {
//            $em->flush();
//
//            $request->getSession()->getFlashBag()->add('success', 'Niveau bien modifié.');
//
//            return $this->redirectToRoute('gs_festival_view', array('id' => $level->getFestival()->getId()));
//        }
//
//        return $this->render('GSFestivalBundle:Level:edit.html.twig', array(
//                    'form' => $form->createView(),
//        ));
//    }
//
//    public function viewAction($id)
//    {
//        // On récupère l'EntityManager
//        $em = $this->getDoctrine()->getManager();
//
//        $level = $em->getRepository('GSFestivalBundle:Level')->find($id);
//        if ($level === null) {
//            throw $this->createNotFoundException("Le niveau d'id " . $id . " n'existe pas.");
//        }
//
//        $couples = $em->getRepository('GSFestivalBundle:Registration')->getSortedForLevel($level);
//        return $this->render('GSFestivalBundle:Level:view.html.twig', array(
//                    'level' => $level,
//                    'couples' => $couples,
//        ));
//    }
//
//    public function deleteAction($id, Request $request)
//    {
//        // On récupère l'EntityManager
//        $em = $this->getDoctrine()->getManager();
//
//        // On récupère l'entité correspondant à l'id $id
//        $level = $em->getRepository('GSFestivalBundle:Level')->find($id);
//        $festival = $level->getFestival();
//
//        // Si l'annonce n'existe pas, on affiche une erreur 404
//        if ($level == null) {
//            throw $this->createNotFoundException("Le niveau d'id " . $id . " n'existe pas.");
//        }
//
//        $form = $this->createFormBuilder()->getForm();
//
//        if ($form->handleRequest($request)->isValid()) {
//            $festival->removeLevel($level);
//            $em->remove($level);
//            $em->flush();
//
//            $request->getSession()->getFlashBag()->add('success', "Le niveau a bien été supprimé.");
//
//            return $this->redirect($this->generateUrl('gs_festival_view', array('id' => $festival->getId())));
//        }
//
//        // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
//        return $this->render('GSFestivalBundle:Level:delete.html.twig', array(
//                    'level' => $level,
//                    'festival' => $festival,
//                    'form' => $form->createView()
//        ));
//    }

}
