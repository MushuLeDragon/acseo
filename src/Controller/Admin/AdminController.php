<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function admin(ContactRepository $contactRepository): Response
    {
        $contactList = $contactRepository->findAll();

        $datas = [];
        foreach ($contactList as $contact) {
            $datas[$contact->getEmail()][] = $contact;
        }

        return $this->render('admin/admin.html.twig', [
            'datas' => $datas,
        ]);
    }

    /**
     * source: ChatGPT
     */
    #[Route('/contact/{id}/toggle-status', name: 'contact_toggle_status')]
    public function toggleContactStatus(Contact $contact, EntityManagerInterface $em): Response
    {
        $contact->setStatus(!$contact->isStatus());
        $em->flush();

        return $this->redirectToRoute('admin');
    }
}
