<?php

namespace App\Controller;

use App\Service\HydrationService;
use App\Service\MaterielService;

use App\Entity\Materiel;
use App\Form\MaterielFormType;
use App\Repository\MaterielRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;


/**
 * @Route("/")
 */
class MaterielController extends AbstractController
{
    /**
     * @Route("/", name="materiel_index", methods={"GET"})
     */
    public function index(MaterielRepository $materielRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $form       = $this->createForm(MaterielFormType::class);
            
        return $this->render('materiel/index.html.twig', [
            'materiels' => $materielRepository->findAll(),
            'form'      => $form->createView()
        ]);
    }

    /**
     * @Route("/hydrate", name="materiel_hydrate", methods={"GET","POST"})
     */
    public function hydrate(Request $request, HydrationService $hydS): Response
    {
        $result = $hydS->insertToDatabase();

        return new JsonResponse($result);
    }

    /**
     * @Route("/search", name="materiel_search", methods={"POST"})
     */
    public function search(Request $request, MaterielRepository $materielRepository): Response
    {
        $materiels = $materielRepository->findBySearch($request->request->all());
        
        return $this->render('materiel/table.html.twig', [
            'materiels' => $materiels
        ]);
    }

    /**
     * @Route("/new", name="materiel_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $materiel = new Materiel();
        $form = $this->createForm(MaterielType::class, $materiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($materiel);
            $entityManager->flush();

            return $this->redirectToRoute('materiel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('materiel/new.html.twig', [
            'materiel' => $materiel,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/show", name="materiel_show", methods={"POST"})
     */
    public function show(Request $request, MaterielRepository $materielRepository): Response
    {
        $materiel = $materielRepository->findById($request->request->get('id'))[0];

        return $this->render('materiel/show.html.twig', [
            'materiel' => $materiel,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="materiel_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Materiel $materiel): Response
    {
        $form = $this->createForm(MaterielType::class, $materiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('materiel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('materiel/edit.html.twig', [
            'materiel' => $materiel,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="materiel_delete", methods={"POST"})
     */
    public function delete(Request $request, Materiel $materiel): Response
    {
        if ($this->isCsrfTokenValid('delete'.$materiel->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($materiel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('materiel_index', [], Response::HTTP_SEE_OTHER);
    }

}
