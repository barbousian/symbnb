<?php

namespace App\Services;

use Twig\Environment;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;

class Pagination {
    private $entityClass;
    private $limit = 10;
    private $currentPage = 1; 
    private $manager;
    private $twig;
    private $route;
    private $templatePath;

    public function __construct(ObjectManager $manager, Environment $twig, RequestStack $request, $templatePath){
        // on veut pouvoir générer du html (twig) dans notre service pour pouvoir l'appeler
        // dans un twig sans faire d'include
        // le parametre $templatePath se trouve dans services.yaml
        $this->manager = $manager;
        $this->twig = $twig;
        $this->route = $request->getCurrentRequest()->attributes->get('_route');
        $this->templatePath = $templatePath;
    }

    public function setTemplatePath($templatePath) {
        $this->templatePath = $templatePath;
        return $this;
    }

    public function getTemplatePath() {
        return $this->templatePath;
    }

    public function setRoute($route) {
        $this->route = $route;
        return $this;
    }

    public function getRoute() {
        return $this->route;
    }

    public function display() {
        $this->twig->display($this->templatePath, [
            'page' => $this->currentPage,
            'nbPages' => $this->getPages(),
            'chemin' => $this->route
        ]);
    }
    public function getData() {
        //1) calculer l'offset
        $offset= $this->currentPage * $this->limit - $this->limit;

        //2 demander au repository d'aller chercher les données
        $data=$this->manager->getRepository($this->entityClass)->findBy(
            [], [], 
            $this->limit,
            $offset);

        //3 renvoyer les données
        return $data;
    }

    public function getPages() {
        $data=$this->manager->getRepository($this->entityClass)->findAll();
        $nbLignes= count($data);
        $nbPages = ceil($nbLignes / $this->limit);

        //3 renvoyer les données
        return $nbPages;
    }
 
    public function getEntityClass() {
        return $this->entityClass;
    }
    
    public function SetEntityClass($entityClass) {
        $this->entityClass = $entityClass;
        return $this;
    }

    public function getLimit() {
        return $this->limit;
    }

    public function SetLimit($limit) {
        $this->limit = $limit;
        return $this;
    }

    public function getCurrentPage() {
        return $this->currentPage;
    }

    public function SetCurrentPage($currentPage) {
        $this->currentPage = $currentPage;
        return $this;
    }
}