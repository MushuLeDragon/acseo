<?php

namespace App\Controller\Admin;

use App\Repository\ContactRepository;
use Symfony\Component\Filesystem\Filesystem;
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
}
