<?php

namespace GS\PersonBundle\Controller;

use GS\PersonBundle\Entity\Person;
use GS\PersonBundle\Form\Type\PersonType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class PersonController extends Controller
{

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function indexAction($page)
    {
        if ($page < 1) {
            throw new NotFoundHttpException('Page "' . $page . '" inexistante.');
        }

        // Ici je fixe le nombre d'annonces par page à 3
        // Mais bien sûr il faudrait utiliser un paramètre, et y accéder via $this->container->getParameter('nb_per_page')
        $nbPerPage = 20;

        // On récupère notre objet Paginator
        $listPersons = $this->getDoctrine()
                ->getManager()
                ->getRepository('GSPersonBundle:Person')
                ->getPersons($page, $nbPerPage)
        ;

        // On calcule le nombre total de pages grâce au count($listAdverts) qui retourne le nombre total d'annonces
        $nbPages = max(1, ceil(count($listPersons) / $nbPerPage));

        // Si la page n'existe pas, on retourne une 404
        if ($page > $nbPages) {
            throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
        }

        return $this->render('GSPersonBundle:Person:index.html.twig', array(
                    'listPersons' => $listPersons,
                    'nbPages' => $nbPages,
                    'page' => $page
        ));
    }

    public function viewAction($id)
    {
        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();

        // Pour récupérer une annonce unique : on utilise find()
        $person = $em->getRepository('GSPersonBundle:Person')->find($id);

        // On vérifie que l'annonce avec cet id existe bien
        if ($person === null) {
            throw $this->createNotFoundException("Le personne d'id " . $id . " n'existe pas.");
        }
        return $this->render('GSPersonBundle:Person:view.html.twig', array(
                    'person' => $person
        ));
    }

    public function addAction(Request $request)
    {
        $person = new Person();
        $form = $this->createForm(PersonType::class, $person);
        $form->add('submit', SubmitType::class);
        
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Personne bien enregistrée.');

            return $this->redirectToRoute('gs_person_view', array('id' => $person->getId()));
        }

        return $this->render('GSPersonBundle:Person:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function editAction($id, Request $request)
    {
        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();

        // Pour récupérer une annonce unique : on utilise find()
        $person = $em->getRepository('GSPersonBundle:Person')->find($id);

        // On vérifie que l'annonce avec cet id existe bien
        if ($person === null) {
            throw $this->createNotFoundException("La personne d'id " . $id . " n'existe pas.");
        }
        
        $form = $this->createForm(PersonType::class, $person, array('method' => 'PUT'));
        $form->add('submit', SubmitType::class);

        if ($form->handleRequest($request)->isValid()) {
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Personne bien modifiée.');

            return $this->redirectToRoute('gs_person_view', array('id' => $person->getId()));
        }
        
        return $this->render('GSPersonBundle:Person:edit.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction($id, Request $request)
    {
        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();

        // On récupère l'entité correspondant à l'id $id
        $person = $em->getRepository('GSPersonBundle:Person')->find($id);

        // Si l'annonce n'existe pas, on affiche une erreur 404
        if ($person === null) {
            throw $this->createNotFoundException("La personne d'id " . $id . " n'existe pas.");
        }

        $form = $this->createFormBuilder()
                ->setMethod('DELETE')
                ->add('delete', SubmitType::class, array('label' => 'Supprimer'))
                ->getForm();
        
        if ($form->handleRequest($request)->isValid()) {
            $em->remove($person);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', "La personne a bien été supprimée.");

            return $this->redirect($this->generateUrl('gs_person_homepage'));
        }

        // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
        return $this->render('GSPersonBundle:Person:delete.html.twig', array(
                    'person' => $person,
                    'form' => $form->createView()
        ));
    }

}
