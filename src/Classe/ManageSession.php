<?php
namespace App\Classes;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ManageSession
{
    private $session;
    private $em;
    public function __construct(EntityManagerInterface $em, RequestStack $stack)
    {
        $this->em = $em;
        $this->session = $stack->getSession();
    }

    // Ajouter un user à l'équipe
    public function addUser($id){
        // Créer un tableau vide si la session vient d'être créee
        $team = $this->session->get('team', []);
        if(empty($team[$id]))
            $team[$id] = 1;

        $this->session->set('team', $team);
    }

    // Récupérer le contenu de la session par son clé : return []
    public function getUser(){
        return $this->session->get('team');
    }

    // Vider une entrée de la session par son clé
    public function remove(){
        return $this->session->remove('team');
    }

    // Retirer un user de l'équipe par son id
    public function delete($id){
        $team = $this->session->get('team', []);
        unset($team[$id]);
        return $this->session->set('team', $team);
    }
}