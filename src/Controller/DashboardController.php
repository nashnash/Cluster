<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\NewPostType;
use App\Repository\EventRepository;
use App\Repository\PostRepository;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DashboardController
 * @package App\Controller
 */
class DashboardController extends AbstractController
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/dashboard", name="dashboard")
     * @param Request $request
     * @param PostRepository $postRepository
     * @param EventRepository $eventRepository
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function index(Request $request, PostRepository $postRepository, EventRepository $eventRepository): Response
    {
        $post = new Post();
        $newPostForm = $this->createForm(NewPostType::class, $post);
        $newPostForm->handleRequest($request);

        if($newPostForm->isSubmitted() && $newPostForm->isValid()) {
            /** @var Post $entity */
            $entity = $newPostForm->getData();
            $entity->setUser($this->getUser());
            $entity->setCreatedAt(new DateTime());
            $this->em->persist($newPostForm->getData());
            $this->em->flush();
            return $this->redirectToRoute('dashboard');
        }

        return $this->render('dashboard/index.html.twig', [
            'newPostForm' => $newPostForm->createView(),
            'posts' => $postRepository->findAll(),
            'events' => $eventRepository->findNext10DaysEvents()
        ]);
    }
}
