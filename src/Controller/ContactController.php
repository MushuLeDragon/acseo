<?php

namespace App\Controller;

use App\Service\ServiceJsonContact;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request): Response
    {
        $defaultData = ['message' => 'Type your message here'];
        $form = $this->createFormBuilder($defaultData)
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('email', EmailType::class)
            ->add('message', TextareaType::class)
            ->add('send', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $datas = $form->getData();
            $datas['createdAt'] = date('Y-m-d');

            $filesystem = new Filesystem();
            $directory = $this->getParameter('kernel.project_dir') . '/public/json/';
            if (!$filesystem->exists($directory)) {
                $filesystem->mkdir($directory);
            }

            $json = new ServiceJsonContact;

            $createJson = $json->createJson($datas['email'], $datas, $directory);
            if ($createJson === false) {
                return new JsonResponse(['message' => 'JSON file NOT created or already exists'], Response::HTTP_CONFLICT);
            }
            if ($createJson === true) {
                return new JsonResponse(['message' => 'JSON file already created, add message to queue'], Response::HTTP_CREATED);
            }

            return new JsonResponse(['message' => 'JSON file created or updated'], Response::HTTP_CREATED);
            return $this->redirectToRoute('contact');
            return $this->redirect('/');
        }

        return $this->render('front/contact.html.twig', [
            'form' => $form,
        ]);
    }
}
