<?php

namespace GS\FestivalBundle\Controller;

use GS\FestivalBundle\Entity\Registration;
use GS\FestivalBundle\Entity\Person;
use GS\FestivalBundle\Form\RegistrationType;
use GS\FestivalBundle\Form\RegistrationEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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

        $form = $this->createFormBuilder()
                ->add('email', EmailType::class, array(
                    'data' => $request->getSession()->get('email'),
                ))
                ->add('next', SubmitType::class, array('label' => 'Next step'))
                ->getForm();
        $request->getSession()->remove('email');

        if ($form->handleRequest($request)->isValid()) {
            $request->getSession()->set('email', $form->get('email')->getData());
            $person = $em->getRepository('GSFestivalBundle:Person')->findOneByEmail($form->get('email')->getData());
            if ($person !== null) {
                $registrations = $em->getRepository('GSFestivalBundle:Registration')->getForPersonAndFestival($festival, $person);
                if ($registrations !== null) {
                    $request->getSession()->getFlashBag()->add('danger', 'Il y a déja une inscription avec cet email.');
                    return $this->redirectToRoute('gs_registration_add', array('id' => $id));
                }
            }
            return $this->redirectToRoute('gs_registration_add2', array('id' => $id));
        }

        return $this->render('GSFestivalBundle:Registration:add.html.twig', array(
                    'festival' => $festival,
                    'form' => $form->createView(),
        ));
    }

    public function add2Action($id, Request $request)
    {
        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();

        // Pour récupérer une annonce unique : on utilise find()
        $festival = $em->getRepository('GSFestivalBundle:Festival')->find($id);
        if ($festival === null) {
            throw $this->createNotFoundException("Le festival d'id " . $id . " n'existe pas.");
        }

        $registration = new Registration();
        $email = $request->getSession()->get('email');
        $request->getSession()->remove('email');
        $person = $em->getRepository('GSFestivalBundle:Person')->findOneByEmail($email);
        if ($person === null) {
            $person = new Person();
            $person->setEmail($email);
        }
        $registration->setPerson($person);
        $form = $this->createForm(RegistrationType::class, $registration, array('festival' => $festival));            

        if ($form->handleRequest($request)->isValid()) {
            $form->get('level')->getData()->addRegistration($registration);
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

    public function assignAction($id, Request $request)
    {
        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();

        $registration = $em->getRepository('GSFestivalBundle:Registration')->find($id);
        if ($registration === null) {
            throw $this->createNotFoundException("L'inscription d'id " . $id . " n'existe pas.");
        }

        if ($registration->getStatus() == 'received' || $registration->getStatus() == 'waiting') {
            $registration->setStatus('pre_assigned');
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

        # Si l'inscription a deja ete validee, il faut etre sur que la mise en liste d'attente est volontaire
        $done = false;
        if ($registration->getStatus() == 'received') {
            $registration->setStatus('pre_waiting');
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Inscription mise en liste d\'attente.');
            $done = true;
        } else if ($registration->getStatus() != 'assigned') {
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
            $registration->setStatus('pre_waiting');
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

    public function emailAction($id, Request $request)
    {
        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();

        // On récupère l'entité correspondant à l'id $id
        $registration = $em->getRepository('GSFestivalBundle:Registration')->find($id);

        // Si l'annonce n'existe pas, on affiche une erreur 404
        if ($registration == null) {
            throw $this->createNotFoundException("Le niveau d'id " . $id . " n'existe pas.");
        }

        if (preg_match('/^pre_/', $registration->getStatus())) {
            $message = \Swift_Message::newInstance()
                    ->setFrom('gsdf@grenobleswing.com')
                    ->setTo($registration->getPerson()->getEmail());
            
            $options['firstName'] = $registration->getPerson()->getFirstName();
            $options['lastName'] = $registration->getPerson()->getLastName();
            $subject = '[GSDF 2016] ';
            switch ($registration->getStatus()) {
                case 'pre_assigned':
                    $subject .= 'Assignment';
                    $registration->setAssignmentDate(new \DateTime());
                    break;
                case 'pre_waiting':
                    $subject .= 'Waiting list';
                    break;
                case 'pre_paid':
                    $subject .= 'Payment received';
                    break;
                case 'pre_cancelled':
                    $subject .= 'Cancellation';
                    break;
                case 'pre_reminder_1':
                    $subject .= 'Reminder';
                    break;
                case 'pre_reminder_2':
                    $subject .= 'Last reminder';
                    break;
            }
            $template = 'GSFestivalBundle:Registration:email_' .
                    preg_replace('/^pre_/', '', $registration->getStatus()) . '.html.twig';
            $message->setSubject($subject)
                    ->setBody($this->renderView($template, $options), 'text/html');
            $this->get('mailer')->send($message);
            
            $registration->setStatus(preg_replace('/^pre_/', '', $registration->getStatus()));
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', "L'email a bien été envoyé.");
        } else {
            $request->getSession()->getFlashBag()->add('info', "L'email a déja été envoyé.");
        }
        
        return $this->redirectToRoute('gs_level_view', array(
                    'id' => $registration->getLevel()->getId(),
        ));
    }

}
