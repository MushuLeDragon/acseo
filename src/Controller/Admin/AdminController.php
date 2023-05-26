<?php

namespace App\Controller\Admin;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function admin(): Response
    {
        $filesystem = new Filesystem();
        $directory = $this->getParameter('kernel.project_dir') . '/public/json/';
        if (!$filesystem->exists($directory)) {
            $filesystem->mkdir($directory);
        }
        $files = array_diff(scandir($directory), array('.', '..'));

        $datas = [];
        foreach ($files as $filename) {
            $str = file_get_contents($directory . $filename);
            $data = json_decode($str, true);
            $email = pathinfo($filename, PATHINFO_FILENAME);
            $datas[$email] = $data[$email];
        }
        // dd($datas);

        return $this->render('admin/admin.html.twig', [
            'datas' => $datas,
        ]);
    }
}
