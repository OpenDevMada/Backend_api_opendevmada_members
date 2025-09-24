<?php
require_once __DIR__ . "/../config/bd.php";
require_once __DIR__ . '/../models/Membre.php';

/**
 * MembreController - gère les opérations d'authentification et CRUD pour la table membres.
 */
class MembreController {

    // Voir un membre avec email
    public function findByMail($email){
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM membres WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Login pour tout le monde
    public function login(array $data){
        global $pdo;

        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        // tester si les données est vide 
        if(empty($email)){
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => "email manquant"]);
            return;
        }

        // Obtenir d'un membre via email
        $membre = $this->findByMail($email);

        try {
            // Verifier mot de passe
            if($membre && password_verify($password, $membre['mot_de_passe'])){
                session_start();
                $_SESSION['membre'] = [
                    'id' => $membre['id'],
                    'nom' => $membre['nom'],
                    'prenom' => $membre['prenom'],
                    'photo_profil' => $membre['photo_profil'],
                    'role' => $membre['role']
                ];

                http_response_code(200);
                echo json_encode([
                    "status" => "success",
                    "message" => "Connexion réussie !",
                    "membre" => [
                        'id' => $membre['id'],
                        'nom' => $membre['nom'],
                        'prenom' => $membre['prenom'],
                        'role' => $membre['role']
                    ]
                ]);
                return;
            }

        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => "Erreur BDD : " . $e->getMessage()
            ]);
            return;
        }

        // Si on arrive ici : échec de connexion
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "message" => "Email ou mot de passe incorrect"
        ]);
    }


    // Deconnexion pour tout le monde
    public function logout(int $id){
        session_start();

        global $pdo;

        // Mettre à jour champ dernier connexion
        // Utiliser CURRENT_TIMESTAMP compatible MySQL/SQLite
        $sql = $pdo->prepare("UPDATE membres SET dernier_connexion = CURRENT_TIMESTAMP WHERE id = :id");

        try{

            // Executer la requette
            $sql->execute([
                'id' => $id,
            ]);

            // Eteindre session
            session_destroy();

            http_response_code(200);
            echo json_encode([
                "status" => "Succès",
                "message" => "Déconnexion réussi !"
            ]);
        }catch (PDOException $e){
            http_response_code(401);
            echo json_encode([
                "status" => "error",
                "message" => "erreur interne"
            ]);
        }

    }

    // Lister les membres pour les stafs uniquement
    public function getMembers(){
        global $pdo;

        $stmt = $pdo->query("SELECT * FROM membres ORDER BY id DESC");

        $membres = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(count($membres) === 0){
            echo json_encode ([
                "status" =>"success",
                "message" => "Aucun membre trouvé",
                "data" => [],
            ]);
        }else{
            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "data" =>$membres
            ]);
        }
    }

    // Voir un membre avec id
    public function getMember(int $id){
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM membres WHERE id = :id");
        $stmt->execute(['id' => $id]);

       $membre =  $stmt->fetch(PDO::FETCH_ASSOC);

       if(!$membre){
        echo json_encode ([
            "status" =>"success",
            "message" => "Aucun membre trouvé",
            "data" => [],
        ]);
        }else{
            echo json_encode([
                "status" => "success",
                "data" =>$membre
            ]);
        }
    }

    
    // Supprimer un membre
    public function deleteMembre(int $id){
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM membres WHERE id = :id");

        try{

            // Executer la requette
            $stmt->execute([
                'id' => $id
            ]);

            echo json_encode([
                "status" => "Succès",
                "message" => "Suppression d'un membre réussi !"
            ]);
        }catch (PDOException $e){
            echo json_encode([
                "status" => "error",
                "message" => "Echec de la supprission de donnée"
            ]);
        }
    }

    // Creer un membre
    public function create(){
        global $pdo;

        // Vérifie que les champs obligatoires sont là
        if (!isset($_POST['nom'], $_POST['prenom'], $_POST['phone'], $_FILES['image'])) {
            echo json_encode(["status" => "error", "message" => "Champs requis manquants."]);
            return;
        }

        // Récupération des champs
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'] ?? null;
        $password = isset($_POST['password']) && $_POST['password'] !== '' ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

        // Gérer la date de naissance optionnelle et tolérer un format invalide
        $date_naissance = null;
        if (!empty($_POST['birthday'])) {
            $dt = DateTime::createFromFormat('d/m/Y', $_POST['birthday']);
            if ($dt instanceof DateTime) {
                $date_naissance = $dt->format('Y-m-d');
            }
        }
        $sexe = $_POST['sexe'] ?? 'male';
        $adresse = $_POST['address'] ?? 'Toamasina Tanamakoa';
        $ville = $_POST['city'] ?? 'TOAMASINA';
        $pays = $_POST['contry'] ?? 'Madagascar';
        $telephone = $_POST['phone'];
        $role = $_POST['role'] ?? 'admin';
        $statut = $_POST['statut'] ?? 'active';


        // Créer le dossier d'image si non existant
        $dir = __DIR__ . "/../public/images/$nom/";
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        // Gérer l'image
        $image = $_FILES['image'];
        $imageName = uniqid() . "_" . basename($image['name']);
        $imageTmp = $image['tmp_name'];
        $imagePath = $dir . $imageName;
        $relativePath = "images/$nom/$imageName";

        // Déplacer l'image dans le dossier définitif
        if (!move_uploaded_file($imageTmp, $imagePath)) {
            echo json_encode(["status" => "error", "message" => "Échec du téléchargement de l'image."]);
            return;
        }

        // Créer une instance membre
        $membre = new Membre(null, $nom, $prenom, $email, $password, $date_naissance,$sexe,$adresse,$ville
        ,$pays, $telephone, $relativePath,null,$role,$statut);

        $sql = $pdo->prepare("INSERT INTO membres (nom, prenom, email, mot_de_passe, date_naissance, sexe, adresse
            ,ville, pays, telephone, photo_profil, role, statut)
            VALUES (:nom, :prenom, :email, :password, :birthday, :sexe, :address, :city, :contry, :phone, :image, :role,:statut)");

        try {
            $sql->execute([
                'nom' => $membre->getNom(),
                'prenom' => $membre->getPrenom(),
                'email' => $membre->getEmail(),
                'password' => $membre->getPassword(),
                'birthday' => $membre->getBirthday(),
                'sexe' => $membre->getSexe(),
                'address' => $membre->getAdresse(),
                'city' => $membre->getVille(),
                'contry' => $membre->getPays(),
                'phone' => $membre->getPhonenumber(),
                'image' => $membre->getPhoto(),
                'role' => $membre->getRole(),
                'statut' => $membre->getStatus()
            ]);

            echo json_encode(["status" => "success", "message" => "Membre créé avec succès !"]);
        } catch (PDOException $e) {
            echo json_encode(["status" => "error", "message" => "Erreur BDD : " . $e->getMessage()]);
        }
    }

    // mettre à jour  un membre
    public function update($id){
        global $pdo;

        // Étape 1 : Vérifie si le membre existe dans la base de données
        $stmt = $pdo->prepare("SELECT * FROM membres WHERE id = ?");
        $stmt->execute([$id]);
        $membreData = $stmt->fetch(PDO::FETCH_ASSOC);
     
        if (!$membreData) {
            echo json_encode(["status" => "error", "message" => "Membre non trouvé."]);
            return;
        }

           // Étape 2 : Récupère les nouvelles données, ou garde les anciennes si rien n'est envoyé
            $email = $_POST['email'] ?? $membreData['email'];
            $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $membreData['mot_de_passe'];
            $adresse = $_POST['address'] ?? $membreData['adresse'];
            $ville = $_POST['city'] ?? $membreData['ville'];
            $telephone = $_POST['phone'] ?? $membreData['telephone'] ;
            $role = $_POST['role'] ?? $membreData['role'];
            $statut = $_POST['statut'] ?? $membreData['statut'];


            // Étape 3 : Gestion du chemin de l'image à partir de la catégorie
            $nom = $membreData['nom'];
        
            if (!$nom) {
                echo json_encode(["status" => "error", "message" => "Nom membre est invalid."]);
                return;
            }
     
            // Crée le dossier si besoin
            $dir = __DIR__ . "/../public/images/$nom/";
            if (!is_dir($dir)) mkdir($dir, 0755, true);
     
            // Étape 4 : Gestion de l’image
            $relativePath = $membreData['photo_profil']; // Par défaut, garde l'ancienne image
            if (!empty($_FILES['image']['name'])) {
                // Supprime l’ancienne image si une nouvelle est envoyée
                $oldPath = __DIR__ . "/../public/" . $membreData['photo_profil'];
                if (file_exists($oldPath)) unlink($oldPath);
        
                // Prépare la nouvelle image
                $image = $_FILES['image'];
                $imageName = uniqid() . "_" . basename($image['name']);
                $imageTmp = $image['tmp_name'];
                $imagePath = $dir . $imageName;
                $relativePath = "images/$nom/$imageName";
        
                // Déplace l’image uploadée dans le bon dossier
                if (!move_uploaded_file($imageTmp, $imagePath)) {
                    echo json_encode(["status" => "error", "message" => "Échec du téléchargement de l’image."]);
                    return;
                }
            }
    
        // Étape 5 : Crée un objet Membre avec les nouvelles données
        $membre = new Membre($id, $nom, null, $email, $password, null,null,$adresse,$ville,null, $telephone, $relativePath,null,$role,$statut);

        // Étape 6 : Prépare et exécute la requête SQL UPDATE
        $sql = $pdo->prepare("UPDATE membres SET email = :email, mot_de_passe = :password, adresse = :address
        ,ville = :city, telephone = :phone, photo_profil= :image, role = :role, statut = :statut WHERE id = :id");

        try {
            $sql->execute([
                'email' => $membre->getEmail(),
                'password' => $membre->getPassword(),
                'address' => $membre->getAdresse(),
                'city' => $membre->getVille(),
                'phone' => $membre->getPhonenumber(),
                'image' => $membre->getPhoto(),
                'role' => $membre->getRole(),
                'statut' => $membre->getStatus(),
                'id' => $membre->getId()
            ]);

            echo json_encode(["status" => "success", "message" => "Le membre a été mis à jour !"]);
        } catch (PDOException $e) {
            echo json_encode(["status" => "error", "message" => "Erreur BDD : " . $e->getMessage()]);
        }
        
    }
}
?>
