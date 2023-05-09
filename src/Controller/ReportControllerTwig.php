<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportControllerTwig extends AbstractController
{
    // 2.1 Skapa en route / som ger en presentation av dig själv inklusive en bild.
    #[Route("/", name: "me")]
    public function mePage(): Response
    {
        return $this->render('me.html.twig');
    }

    // 2.2 Skapa en route /about som berättar om kursen mvc och dess syfte.
    #[Route("/about", name: "about")]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    // 2.3 Skapa en route /report där du samlar dina redovisningstexter för kursens kmom.
    #[Route("/report", name: "report")]
    public function report(): Response
    {
        return $this->render('report.html.twig');
    }
}
