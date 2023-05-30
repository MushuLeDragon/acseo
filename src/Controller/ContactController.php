<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Service\ServiceJsonContact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ContactType::class, new Contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $datas = $form->getData();
            $datas->setJson($datas->getCreatedAt()->getTimestamp());

            $em->persist($datas);
            $em->flush();

            $filesystem = new Filesystem();
            $directory = $this->getParameter('kernel.project_dir') . '/public/json/';
            if (!$filesystem->exists($directory)) {
                $filesystem->mkdir($directory);
            }

            $json = $this->json(
                $datas,
                headers: ['Content-Type' => 'application/json;charset=UTF-8']
            );
            $serviceJsonContact = new ServiceJsonContact;
            $createdJson = $serviceJsonContact->createJson($datas, $json, $directory);
            if ($createdJson === false) {
                echo $createdJson;
                return new JsonResponse(['message' => 'JSON file NOT created or already exists'], Response::HTTP_CONFLICT);
            }
            // if ($createdJson === true) {
            //         return new JsonResponse(['message' => 'JSON file already created, add message to queue'], Response::HTTP_CREATED);
            //     }

            // return new JsonResponse(['message' => 'JSON file created'], Response::HTTP_CREATED);
            return $this->redirectToRoute('contact');
            //     return $this->redirect('/');
        }

        return $this->render('front/contact.html.twig', [
            'form' => $form,
        ]);
    }
}
