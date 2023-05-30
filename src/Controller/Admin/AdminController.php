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

        $jsonFileName = $contact->getJson() . '.json';
        $filePath = $this->getParameter('kernel.project_dir') . '/public/json/' . $jsonFileName;
        
        $content = file_get_contents($filePath);
        $data = json_decode($content, true);
        $data['status'] = $contact->isStatus();
        $updatedJsonData = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($filePath, $updatedJsonData);

        return $this->redirectToRoute('admin');
    }
}
