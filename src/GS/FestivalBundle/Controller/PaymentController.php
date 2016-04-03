<?php

namespace GS\FestivalBundle\Controller;

use GS\FestivalBundle\Entity\Payment;
use GS\FestivalBundle\Form\Type\PaymentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class PaymentController extends Controller
{

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
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

}
