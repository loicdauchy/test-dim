<?php

namespace App\Controller;

use App\Entity\Response as EntityResponse;
use App\Form\AddResponseType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResponseController extends AbstractController
{
    #[Route('/response/{id}', name: 'app_response')]
    public function index(int $id, PostRepository $postRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = $postRepository->find($id);

        $response = new EntityResponse();
        $form = $this->createForm(AddResponseType::class, $response);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $response->setOwner($this->getUser());
            $response->setPost($post);
            $entityManager->persist($response);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('response/index.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }
}
