<?php
// Class Commentaire
class Category {
    
    public int $id;
    public string $name;

    public function __construct($id, $name) {
        $this->id = $id;
        $this->name = $name;
    }
}

// Class Commentaire
class Commentaire {
    protected int $id;
    public string $content;
    public string $auteur;
    
    public function __construct($id='', $content,$auteur='') {
        $this->id = $id;
        $this->content = $content;
        $this->auteur=$auteur;
    }

    public function getId(){return $this->id;}
    public function getContent() {return $this->content;}
    public function getAuteur() {return $this->auteur;}
}

// 3. Class Article
class Article {
    private int $id;
    public string $title;
    public string $content;
    public string $status; 
    public string $auteurName;
    public string $categories;
    private array $comments=[];

    public function __construct($id, $title, $content, $status, $auteurName, $categories = "", $comments = []) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->status = $status;
        $this->auteurName = $auteurName;
        $this->categories = $categories;
        $this->comments= $comments;
    }

    public function getId() { return $this->id; }
    public function getTitle() { return $this->title;}
    public function getStatus() { return $this->status;}
    public function getAuteurName() { return $this->auteurName;}
    public function  getComments(){return $this->comments;} 
    public function  addComment($cmt){$this->comments[] = $cmt;}
}

//Class User
class User { 
    protected int $id;
    protected string $username;
    protected string $email;
    protected string $password;
    protected string $role;
    
    function __construct($id, $username, $email, $password, $role){
         $this->id = $id;
         $this->username = $username;
         $this->email = $email;
         $this->password = $password;
         $this->role = $role;
    }

   

    public function estAuteur(): bool { return $this->role === "auteur"; }
    public function estAdmin(): bool { return $this->role === "admin"; }
    public function estEditeur(): bool { return $this->role === "editeur"; }

    public function getUsername(){ return $this->username; }
    public function getId(){ return $this->id; }
    
   public function getEmail(){return $this->email;}
    public function getPassword(){ return $this->password;}
    public function getRole(){return $this->role;} 
}

// Class Auteur
class Auteur extends User {
    private array $Myarticle=[];

    function __construct($id, $username, $email, $password, $role = "auteur"){
        parent::__construct($id, $username, $email, $password, $role);
    }
    
    public function createArticle(Article $article) {
        $this->myArticles[] = $article;
        echo "Article '{$article->title}' créé par {$this->username}.\n";
    }

  
}

//Class Moderateur
class Moderateur extends User {
    function __construct($id, $username, $email, $password, $role = "moderateur"){
        parent::__construct($id, $username, $email, $password, $role);
    }

   
}


class Editeur extends Moderateur {
    function __construct($id, $username, $email, $password){
        parent::__construct($id, $username, $email, $password, "editeur");    
    }   
}


class Admin extends Moderateur {
    function __construct($id, $username, $email, $password) {
        parent::__construct($id, $username, $email, $password, "admin");    
    }

    public function cree_utilisateurs(User $user, array &$userTable) {
        $userTable[] = $user;
        echo "Utilisateur {$user->getUsername()} a été ajouté.\n";
    }

    
}

class Collection {

    private static $instance = null;
    public array $users = [];
    public array $articles = [];
    private array $categories = [];
    private $current_user = null;

    private function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->users = [
            new Admin(1, "alice", "ahmedoub@gmail.com", "pass123", "Admin"),
            new Auteur(3, "bob", "bob@gmail.com", "pass123", "Auteur"),
            new Auteur(4, "john", "john@gmail.com", "pass123", "Auteur"), 
            new Admin(2, "ayoub", "ayoub@gmail.com","12345678","Admin"),
            new Editeur(5, "ahmed", "oubelkacem@gmail.com","11111111","Editeur")
        ];

        
        $this->articles = [
            new Article(15, "the range", "....", "published", "bob", "General"),
            new Article(22, "power", "....", "draft", "john", "Sport"),
            new Article(1, "Worlf cup 2022", "....", "published", "aya", "Finance")
        ];

        $this->categories = [
            new Category(2, "sport"),
            new Category(3, "finance")
        ];
  
    if (isset($_SESSION['user_id'])) {
            $this->restoreSessionUser($_SESSION['user_id']);
        }
        
    }

    public function afficherMesArticles(){
        echo "\n--- Articles de {$this->current_user->getUsername()} ---\n";
         echo "ID   | Title                | Author          \n";
        echo "-----+----------------------+-----------------\n";
        foreach ($this->articles as $article) {
        if($this->current_user->getUsername()==$article->getAuteurName()){
            printf(
                "%-4s | %-20s | %-15s \n",
                $article->getId(), 
                substr($article->getTitle(), 0, 20), 
                $article->getAuteurName()
            );}
        }
    }




    public  function getTableUsers(){
            return $this->users;
        }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function login($usernameInput, $passwordInput) {
        foreach ($this->users as $user) {
            if ( $user->getUsername() === $usernameInput && $user->getPassword() === $passwordInput ) 
                {
                $this->current_user = $user;
                $_SESSION['user_id'] = $user->getId();
                $_SESSION['username'] = $user->getUsername();
                
                echo "connected \n";
                return;
            }
        }
       echo "eshec de connection \n";
    }

    public function logout(): void {
        $this->current_user = null;
        session_unset();
    }

     public function afficherUseridLogin() {
        if ($this->current_user == null){
          echo "No user is connected!";  
        }
        $name=$this->current_user->getUsername();
        $role=$this->current_user->getRole();
        echo "Utilusateur Connectee : username: $name, Role: $role \n";
    }

    public function getCurrentUser() {
        return $this->current_user;
    }

    public function isLoggedIn(): bool {
        return $this->current_user !== null ? "Déconnexion OK\n" : "Problème déconnexion\n";
    }

    public function displayAllArticles(): void {
        echo "ID   | Title                | Author          \n";
        echo "-----+----------------------+-----------------\n";
        foreach ($this->articles as $article) {
            printf(
                "%-4s | %-20s | %-15s \n",
                $article->getId(), 
                substr($article->getTitle(), 0, 20), 
                $article->getAuteurName()
            );
        }
    }

      
     public function displayAllUsers(): void {
        echo "\nID   | Username             | Email                     | Role      \n";
    echo "-----+----------------------+---------------------------+-----------\n";
    foreach ($this->users as $user) {
        printf(
            "%-4s | %-20s | %-25s | %-9s \n", 
            $user->getId(), 
            substr($user->getUsername(), 0, 20), 
            substr($user->getEmail(), 0, 25),
            $user->getRole()
        );
    }
    }

    private function restoreSessionUser($id) {
        foreach ($this->users as $user) {
            if ($user->getId() == $id) {
                $this->current_user = $user;
                return;
            }
        }
    }

     public function supprimerArticle(int $articleId){
        if (!$this->isLoggedIn() || !($this->current_user instanceof Admin) && !($this->current_user instanceof Editeur)) {
            echo "Erreur: you don't have  permession to delete this article.\n";
            return;
        }
        foreach($this->articles as $index => $article){
            if($article->getId() == $articleId){
                unset($this->articles[$index]);
                $r=$this->current_user->getRole();
                $name=$this->current_user->getUsername();
                echo "Article ID $articleId supprimé par $name  de role $r .\n";
                return;
            }
        }
        echo "Article introuvable.\n";
    }


 

    public function supprimerArticleParAuteur(int $articleId){
        if (!$this->isLoggedIn() && !($this->current_user instanceof Auteur)) {
            echo "Erreur: you don't have  permession to delete this article.\n";
            return;
        
     }
        foreach($this->articles as $index => $article){
            if($article->getId() == $articleId && $article->getAuteurName()==$this->current_user->getUsername()){
                $title=$this->articles[$index]->getTitle();
                unset($this->articles[$index]);
                echo " supprimé mon Article : $title de ID $articleId .\n";
                return;
            }
        }
        echo "Article introuvable.\n";
    }
     
      public function supprimer_utilisateurs($userId) {
        if (!$this->isLoggedIn() || !($this->current_user instanceof Admin)) {
            echo "Erreur: Seul un Admin connecté peut supprimer des utilisateurs.\n";
            return;
        }

        foreach ($this->users as $i => $user) {
            if ($user->getId() == $userId) {
                unset($this->users[$i]);
                
                $this->users = array_values($this->users); 
                
                echo "Succès: Utilisateur ID $userId supprimé.\n";
                return;
            }
        }
        
        echo "Erreur: Utilisateur ID $userId introuvable.\n";
    }

     public function cree_utilisateurs(User $user) {
     if (!$this->isLoggedIn() || !($this->current_user instanceof Admin)) {
            echo "Erreur: Seul un Admin connecté peut cree des utilisateurs.\n";
            return;
        }
        $this->users[]= $user;
        echo "Utilisateur {$user->getUsername()} a été ajouté.\n";
    }

   public function RechrecheArticleByid($id){
    foreach($this->articles as $art){
            if($art->getId()==$id){
                return $art;
            }
        }
   }

    public function cree_Commentaire($contenu ,$idArticle){
       foreach($this->articles as $art){
            if($art->getId()==$idArticle){
              $comm=new Commentaire(rand(1, 1000),$contenu);
               $art->addComment( $comm);
               echo "commentaire a ete ajouter avec Succès";
               return;
            }
        }
         echo "article introuvable!!";
    }


   public function Display_Commentaires() {
    echo "\n--- LISTE DES COMMENTAIRES ---\n";
    echo "ID Art | ID Com | Auteur Com      | Contenu             \n";
    echo "-------+--------+-----------------+---------------------\n";

    foreach ($this->articles as $art) {
        $comments = $art->getComments();
        
        if (!empty($comments)) {
            foreach ($comments as $cmt) {
                if ($cmt instanceof Commentaire) {
                    printf(
                        "%-6s | %-6s | %-15s | %-20s \n", 
                        $art->getId(),          
                        $cmt->getId(),          
                        substr($cmt->getAuteur(), 0, 15), 
                        substr($cmt->getContent(), 0, 20) 
                    );
                }
            }
        }
    }
    echo "------------------------------------------------------\n";
}
           
            
        }
         
    



    


// $collection = Collection::getInstance();
// echo "\n--- Liste des Articles ---\n";
// $collection->displayAllArticles();
// $collection->logout();
// echo $collection->isLoggedIn() ;
// $collection->logout();


// echo "\n--- TEST 1---\n";
// $collection->login('ahmed','11111111');
// $collection->supprimerArticle(22);
// $collection->displayAllArticles();
// $collection->logout();


// echo "\n--- TEST 2---\n";
// $collection->login("alice", "pass123");
//  $user=new Admin (2, "leila", "leila@gmail.com", "12345678", "Admin");
// $collection->cree_utilisateurs($user);
// $collection->afficherUseridLogin();

// echo "\n--- TEST 3 : supprimer user leila de id= 2  ---\n";
// $collection->supprimer_utilisateurs(2);
// $collection->logout();

// echo "\n--- TEST 3 : supprimer user id= 22 par Auteur  ---\n";
// $collection->login("bob", "pass123");
// $collection->supprimerArticleParAuteur(22);
// --- AJOUTER CETTE CLASSE À LA FIN DE VOTRE FICHIER ---

class Menu {
    private Collection $collection;

    public function __construct() {
        $this->collection = Collection::getInstance();
    }

    private function prompt($message) {
        echo $message . ": ";
        return trim(fgets(STDIN));
    }

    public function start() {
        while (true) {
            $this->clearScreen();
            
            $currentUser = $this->collection->getCurrentUser();

            if ($currentUser === null) {
                $this->afficherMenuVisiteur();
            } else {
                $this->afficherMenuUtilisateur($currentUser);
            }
        }
    }

    private function afficherMenuVisiteur() {
        echo "\n=== MENU PRINCIPAL ===\n";
        echo "1. Se connecter\n";
        echo "2. Voir les articles (Lecture seule)\n";
        echo "3.cree un commentaire \n";
        echo "4.Afficher les commentaire\n";
        echo "0. Quitter\n"; 
        echo "----------------------\n";

        $choix = $this->prompt("Votre choix");

        switch ($choix) {
            case '1':
                $user = $this->prompt("Username");
                $pass = $this->prompt("Password");
                $this->collection->login($user, $pass);
                $this->pause();
                break;
            case '2':
                $this->collection->displayAllArticles();
                $this->pause();
                break;
            case '3':
                $this->collection->displayAllArticles();
                $commentaire = $this->prompt("contenu de commentaire:");
                $id = $this->prompt("id de l'article:");
                $this->collection->cree_Commentaire($commentaire,$id);
                $this->pause();
                break; 
                case '4':
                $this->collection->Display_Commentaires();
                $this->pause();
                break;   
            case '0':
                echo "Au revoir !\n";
                exit;
            default:
                echo "Choix invalide.\n";
                $this->pause();
        }
    }

    private function afficherMenuUtilisateur(User $user) {
        echo "\n=== ESPACE MEMBRE : " . $user->getUsername() . " (" . $user->getRole() . ") ===\n";
        
        echo "1. Afficher tous les articles\n";
        

        if ($user instanceof Admin) {
            echo "2. [ADMIN] Afficher les utilisateurs\n";
            echo "3. [ADMIN] Créer un utilisateur\n";
            echo "4. [ADMIN] Supprimer un utilisateur\n";
        }
        

        if ($user instanceof Admin || $user instanceof Editeur) {
            echo "5. [GESTION] Supprimer un article (Global)\n";
        }


        if ($user instanceof Auteur) {
            echo "6. [AUTEUR] Mes articles\n";
            echo "7. [AUTEUR] Supprimer un de mes articles\n";
        }


        echo "9. Se déconnecter\n";
        echo "0. Quitter\n";
        echo "----------------------\n";

        $choix = $this->prompt("Votre choix");

        switch ($choix) {
            case '1':
                $this->collection->displayAllArticles();
                break;
            
            case '2':
                if ($user instanceof Admin) {
                    $this->collection->displayAllUsers();
                } else { echo "Accès refusé.\n"; }
                break;

            case '3':
                if ($user instanceof Admin) {
                    $this->creerUtilisateurWizard();
                } else { echo "Accès refusé.\n"; }
                break;

            case '4':
                if ($user instanceof Admin) {
                    $this->collection->displayAllUsers();
                    $id = (int)$this->prompt("ID de l'utilisateur à supprimer");
                    $this->collection->supprimer_utilisateurs($id);
                } else { echo "Accès refusé.\n"; }
                break;

            case '5':
                if ($user instanceof Admin || $user instanceof Editeur) {
                    $this->collection->displayAllArticles();
                    $id = (int)$this->prompt("ID de l'article à supprimer");
                    $this->collection->supprimerArticle($id);
                } else { echo "Accès refusé.\n"; }
                break;
            
            case '6':
                if ($user instanceof Auteur) {
                    $this->collection->afficherMesArticles(); 
                }
                break;

            case '7':
                if ($user instanceof Auteur) {
                    $this->collection->displayAllArticles();
                    $id = (int)$this->prompt("ID de VOTRE article à supprimer");
                    $this->collection->supprimerArticleParAuteur($id);
                }
                break;

            case '9':
                $this->collection->logout();
                echo "Déconnexion réussie.\n";
                break;

            case '0':
                exit;

            default:
                echo "Choix invalide.\n";
        }
        $this->pause();
    }

    private function creerUtilisateurWizard() {
        echo "\n--- Création d'un nouvel utilisateur ---\n";
        $roleType = $this->prompt("Rôle (1: Admin, 2: Editeur, 3: Auteur)");
        $username = $this->prompt("Username");
        $email = $this->prompt("Email");
        $password = $this->prompt("Password");
        $id = rand(100, 999); 

        $newUser = null;
        switch($roleType) {
            case '1': $newUser = new Admin($id, $username, $email, $password); break;
            case '2': $newUser = new Editeur($id, $username, $email, $password); break;
            case '3': $newUser = new Auteur($id, $username, $email, $password); break;
            default: echo "Rôle invalide, annulation.\n"; return;
        }

        $this->collection->cree_utilisateurs($newUser);
    }

    private function pause() {
        echo "\n(Appuyez sur Entrée pour continuer...)";
        fgets(STDIN);
    }

    private function clearScreen() {
        echo "\n\n\n";
    }
}

// --- LANCEMENT DU MENU ---
$menu = new Menu();
$menu->start();