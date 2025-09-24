<?php

    // Definition classe memebre

    class Membre {
        private $id;
        private $nom;
        private $prenom;
        private $email;
        private $mot_de_passe;
        private $date_naissance;
        private $sexe;
        private $adresse;
        private $ville;
        private $pays;
        private $telephone;
        private $photo_profil;
        private $dernier_connexion;
        private	$role;
        private $statut;

        // fonction constructeur
        public function __construct(
            $id=null, $nom=null, $prenom=null, $email=null, $mot_de_passe=null, $date_naissance=null,$sexe=null,
            $adresse=null,$ville=null,$pays=null, $telephone=null, $photo_profil=null,$dernier_connexion=null,$role=null,$statut=null
        ){
            $this->id = $id;
            $this->nom = $nom;
            $this->prenom = $prenom;
            $this->email = $email;
            $this->mot_de_passe = $mot_de_passe;
            $this->date_naissance = $date_naissance;
            $this->sexe = $sexe;
            $this->adresse = $adresse;
            $this->ville = $ville;
            $this->pays = $pays;
            $this->telephone = $telephone;
            $this->photo_profil = $photo_profil;
            $this->dernier_connexion =$dernier_connexion;
            $this->role = $role;
            $this->statut = $statut;
        }

        // GETTERS ET SETTERS
        public function getId(){
            return $this->id;
        }

        public function setId($id){
            $this->id = $id;
        }

        public function getNom(){
            return $this->nom;
        }

        public function setNom($nom){
            $this->nom = $nom;
        }

        public function getPrenom(){
            return $this->prenom;
        }
        public function setPrenom($prenom){
            $this->prenom = $prenom;
        }

        public function getEmail(){
            return $this->email;
        }

        public function setEmail($email){
            $this->email = $email;
        }

        public function getPassword(){
            return $this->mot_de_passe;
        }

        public function setPassword($password){
            $this->mot_de_passe = $password;
        }

        public function getBirthday(){
            return $this->date_naissance;
        }

        public function setBirthday($birthday){
            $this->date_naissance = $birthday;
        }
       
        public function getSexe(){
            return $this->sexe;
        }

        public function setSexe($sexe){
            $this->sexe = $sexe;
        }

        public function getAdresse(){
            return $this->adresse;
        }
        public function setAdresse($adresse){
            $this->adresse = $adresse;
        }

        public function getVille(){
            return $this->ville;
        }

        public function setVille($ville){
            $this->ville = $ville;
        }

        public function getPays(){
            return $this->pays;
        }

        public function setPays($pays){
            $this->pays = $pays;
        }

       public function getPhonenumber(){
            return $this->telephone;
       }

       public function setPhonenumber($phoneNumber){
        $this->telephone = $phoneNumber;
       }
       
       public function getPhoto(){
            return $this->photo_profil;
       }

       public function setPhoto($photo){
            $this->photo_profil = $photo;
       }
       
       public function getLastConexion(){
            return $this->dernier_connexion;
       }

       public function setLastConexion($lastconnexion){
            $this->dernier_connexion = $lastconnexion;
       }

       public function getRole(){
            return $this->role;
       }

       public function setRole($role){
            return $this->role = $role;
       }
      
      
       public function getStatus(){
            return $this->statut;
       }

       public function setStatus($statut){
            $this->statut = $statut;
       }
}