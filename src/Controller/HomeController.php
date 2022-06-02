<?php

namespace App\Controller;

use App\Entity\Marque;
use App\Entity\Voiture;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
        $nom = "Benoit";
        $maVariableAlea = rand(0, 100);

        return $this->render('home/index.html.twig', ['name' => $nom, 'titi' => $maVariableAlea]);
    }
    /**
     * @Route("/page2", name="app_page2")
     */
    public function page2(ManagerRegistry $doctrine): Response
    {
        $voiture = $doctrine->getRepository(Voiture::class)->findBy(['year' => 2000], ['color' => 'ASC']);

        return $this->render('home/page2.html.twig', ['listVoiture' => $voiture]);
    }

    /**
     * @Route("/page3", name="app_page3")
     */
    public function page3(ManagerRegistry $doctrine): Response
    {
        $marque = $doctrine->getRepository(Marque::class)->findBy(['nom' => 'Peugeot']);

        return $this->render('home/page3.html.twig', ['listMarque' => $marque]);
    }

    /**
     * @Route("/nouvellevoiture", name="app_nouvellevoiture")
     */
    public function nouvellevoiture(ManagerRegistry $doctrine): JsonResponse
    {
        $marque = $doctrine->getRepository(Marque::class)->findOneById(1);

        $voiture = new Voiture();
        $voiture->setName("Passat");
        $voiture->setColor("Jaune canary");
        $voiture->setYear("1980");
        $voiture->setMarque($marque);

        $entityManager = $doctrine->getManager();
        $entityManager->persist($voiture);
        
        $entityManager->flush();

        return $this->json(['result' => 'ok']);
    }

    /**
     * @Route("/editionvoiture", name="app_editionvoiture")
     */
    public function editionvoiture(ManagerRegistry $doctrine): JsonResponse
    {
        $voiture = $doctrine->getRepository(Voiture::class)->findOneById(5);
        $voiture->setColor("Noir");

        $entityManager = $doctrine->getManager();
        $entityManager->persist($voiture);
        
        $entityManager->flush();

        return $this->json(['result' => 'ok']);
    }

    /**
     * @Route("/suppressionvoiture", name="app_suppressionvoiture")
     */
    public function suppressionvoiture(ManagerRegistry $doctrine): JsonResponse
    {
        $voiture = $doctrine->getRepository(Voiture::class)->findOneById(5);

        $entityManager = $doctrine->getManager();
        $entityManager->remove($voiture);
        
        $entityManager->flush();

        return $this->json(['result' => 'ok']);
    }
}
