<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller{
    /**
    * @Route("/hello/{prenom}/age/{age}", name="hello-prenom-age") 
    * @Route("/hello", name="hello_base") 
    * @Route("/hello/{prenom}", name="hello_prenom") 
    * Montre la page qui dit bonjour
     */
    
    public function hello($prenom = "anonyme", $age=0) {
        return $this->render(
            'hello.html.twig',
            [
                'prenom' => $prenom,
                'age'=> $age
            ]);
    }
    /**
     * @Route("/", name="homepage")
     *
     */
    public function home() {
        $prenoms = ["Lior" => 18, "Joseph" => 22, "Anne" => 12];
        return $this->render(
            'home.html.twig',
            [
                'age'=> 16,
                'title' => "Bonjour à tous",
                'tableau' => $prenoms
            ]
        );
    }
}
?>