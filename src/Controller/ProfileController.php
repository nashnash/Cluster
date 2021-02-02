<?php

namespace App\Controller;

use App\Form\ProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


class ProfileController extends AbstractController
{

    /**
     * @Route("/profile", name="profile_show")
     */
    public function index(): Response
    {

        $user=$this->getUser();
        return $this->render('profile/index.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/profile_edit", name="profile_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request)
    {
        $user=$this->getUser();
        $form = $this->createForm(ProfileType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profile_show', [
                'user' => $user,
            ]);
        }

        return $this->render('profile/edit-profile.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }
}
