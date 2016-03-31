<?php

namespace GS\FestivalBundle\Controller;

use GS\FestivalBundle\Entity\Festival;
use GS\FestivalBundle\Form\FestivalType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FestivalController extends Controller
{

    public function indexAction($page)
    {
        if ($page < 1) {
            throw new NotFoundHttpException('Page "' . $page . '" inexistante.');
        }

        // Ici je fixe le nombre d'annonces par page à 3
        // Mais bien sûr il faudrait utiliser un paramètre, et y accéder via $this->container->getParameter('nb_per_page')
        $nbPerPage = 5;

        // On récupère notre objet Paginator
        $listFestivals = $this->getDoctrine()
                ->getManager()
                ->getRepository('GSFestivalBundle:Festival')
                ->getFestivals($page, $nbPerPage)
        ;

        // On calcule le nombre total de pages grâce au count($listAdverts) qui retourne le nombre total d'annonces
        $nbPages = max(1, ceil(count($listFestivals) / $nbPerPage));

        // Si la page n'existe pas, on retourne une 404
        if ($page > $nbPages) {
            throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
        }

        return $this->render('GSFestivalBundle:Festival:index.html.twig', array(
                    'listFestivals' => $listFestivals,
                    'nbPages' => $nbPages,
                    'page' => $page
        ));
    }

    public function viewAction($id)
    {
        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();

        // Pour récupérer une annonce unique : on utilise find()
        $festival = $em->getRepository('GSFestivalBundle:Festival')->find($id);

        // On vérifie que l'annonce avec cet id existe bien
        if ($festival === null) {
            throw $this->createNotFoundException("Le festival d'id " . $id . " n'existe pas.");
        }
        return $this->render('GSFestivalBundle:Festival:view.html.twig', array(
                    'festival' => $festival
        ));
    }

    public function addAction(Request $request)
    {
        $festival = new Festival();
        $form = $this->createForm(FestivalType::class, $festival);
        
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($festival);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Festival bien enregistré.');

            return $this->redirectToRoute('gs_festival_view', array('id' => $festival->getId()));
        }

        return $this->render('GSFestivalBundle:Festival:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function editAction($id, Request $request)
    {
        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();

        // Pour récupérer une annonce unique : on utilise find()
        $festival = $em->getRepository('GSFestivalBundle:Festival')->find($id);

        // On vérifie que l'annonce avec cet id existe bien
        if ($festival === null) {
            throw $this->createNotFoundException("Le festival d'id " . $id . " n'existe pas.");
        }
        
        $form = $this->createForm(FestivalType::class, $festival);
        
        if ($form->handleRequest($request)->isValid()) {
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Festival bien modifié.');

            return $this->redirectToRoute('gs_festival_view', array('id' => $festival->getId()));
        }
        
        return $this->render('GSFestivalBundle:Festival:edit.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    public function deleteAction($id, Request $request)
    {
        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();

        // On récupère l'entité correspondant à l'id $id
        $festival = $em->getRepository('GSFestivalBundle:Festival')->find($id);

        // Si l'annonce n'existe pas, on affiche une erreur 404
        if ($festival == null) {
            throw $this->createNotFoundException("Le festival d'id " . $id . " n'existe pas.");
        }

        $form = $this->createFormBuilder()->getForm();
        
        if ($form->handleRequest($request)->isValid()) {
            $em->remove($festival);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', "Le festival a bien été supprimé.");

            return $this->redirect($this->generateUrl('gs_festival_homepage'));
        }

        // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
        return $this->render('GSFestivalBundle:Festival:delete.html.twig', array(
                    'festival' => $festival,
                    'form' => $form->createView()
        ));
    }

}
