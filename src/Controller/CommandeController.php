<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Entity\DetailCommande;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commande')]
class CommandeController extends AbstractController
{
    #[Route('/', name: 'app_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository): Response
    {
        $user = $this->getUser();
        $id = $user->getId();
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('app_detail_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
       // return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }


    #[Route('/modifier', name: 'app_commande_modifier', methods: ['POST'])]
    public function modifierStatut(Request $request, EntityManagerInterface $entityManager): Response
    {
        $var = $request->request->get('var');
        $commandeId = $request->request->get('id');
    
        $commande = $entityManager->getRepository(Commande::class)->find($commandeId);
    
        if (!$commande) {
            // Gérer le cas où la commande n'est pas trouvée
            // Redirection ou autre action appropriée
        }
    
        $commande->setStatut($var);
        $commande->setDate($commande->getDate());
        
    
        // Rechargez la commande depuis la base de données
        $commande = $entityManager->getRepository(Commande::class)->find($commandeId);
        $entityManager->persist($commande);
        $entityManager->flush();
        
        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    #[Route('/{id}', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $id = $commande->getId();
    
        if ($this->isCsrfTokenValid('delete'.$id, $request->request->get('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/mescommandes/{id}', name: 'app_commande_mescommandes', methods: ['GET', 'POST'])]
    public function mescommandes(CommandeRepository $commandeRepository,int $id): Response
    {
        $commandes = $commandeRepository->findAllCommandeById($id);
        return $this->render('commande/commandes.html.twig', [
            'commandes' => $commandes,
        ]);
    }


}
