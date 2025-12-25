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
    
    public function __construct($id, $content) {
        $this->id = $id;
        $this->content = $content;
    }
}

// 3. Class Article
class Article {
    private int $id;
    public string $title;
    public string $content;
    public string $status; 
    public string $auteurName;
    public string $categories;

    public function __construct($id, $title, $content, $status, $auteurName, $categories = "", $comments = []) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->status = $status;
        $this->auteurName = $auteurName;
        $this->categories = $categories;
    }

    public function getId() { return $this->id; }
    
    public function getTitle() { return $this->title; }
    
    public function getAuteurName() { return $this->auteurName; }
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

    public function afficherUser() {
        return "ID: {$this->id} ,username: {$this->username} , Role: {$this->role} <br>";
    }

    public function estAuteur(): bool { return $this->role === "auteur"; }
    public function estAdmin(): bool { return $this->role === "admin"; }
    public function estEditeur(): bool { return $this->role === "editeur"; }

    public function getUsername(){ return $this->username; }
    public function getId(){ return $this->id; }
    
    
    public function getPassword(){ return $this->password; }
}

// Class Auteur
class Auteur extends User {
    public array $myArticles = [];

    function __construct($id, $username, $email, $password, $role = "auteur"){
        parent::__construct($id, $username, $email, $password, $role);
    }

    public function createArticle(Article $article) {
        $this->myArticles[] = $article;
        echo "Article '{$article->title}' créé par {$this->username}.\n";
    }

    public function afficherMesArticles() {
        echo "\n--- Articles de {$this->username} ---\n";
        foreach ($this->myArticles as $article) { 
            echo "Title: {$article->title} (Status: {$article->status})\n";
        }
    }
}

//Class Moderateur
class Moderateur extends User {
    function __construct($id, $username, $email, $password, $role = "moderateur"){
        parent::__construct($id, $username, $email, $password, $role);
    }

    public function supprimerArticle(int $articleId, array &$articleList){
        foreach($articleList as $index => $article){
            if($article->getId() == $articleId){
                unset($articleList[$index]);
                echo "Article ID $articleId supprimé par le modérateur.\n";
                return;
            }
        }
        echo "Article introuvable.\n";
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

    public function supprimer_utilisateurs($userId, array &$userTable){
        foreach($userTable as $i => $user){
            if($user->getId() == $userId){
                unset($userTable[$i]);
                echo "Utilisateur ID $userId supprimé.\n";
                return;
            }
        }
        echo "Utilisateur non trouvé.\n";
    }
}

class Collection {

    private static $instance = null;
    public array $users = [];
    public array $articles = [];
    private array $categories = [];
    private $current_user = null;

    private function __construct() {
        $this->users = [
            new Auteur(1, "alice", "ahmedoub@gmail.com", "pass123", "admin"),
            new Auteur(3, "bob", "bob@gmail.com", "pass123", "admin"),
            new Auteur(4, "john", "john@gmail.com", "pass123", "admin")
        ];

        
        $this->articles = [
            new Article(15, "the range", "....", "published", "alice", "General"),
            new Article(22, "power", "....", "draft", "Amine", "Sport"),
            new Article(1, "Worlf cup 2022", "....", "published", "aya", "Finance")
        ];

        $this->categories = [
            new Category(2, "sport"),
            new Category(3, "finance")
        ];

        
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

    public function login($usernameInput, $passwordInput): bool {
        foreach ($this->users as $user) {
            if (
                $user->getUsername() === $usernameInput &&
                $user->getPassword() === $passwordInput 
            ) {
                $this->current_user = $user;
                return true;
            }
        }
        return false;
    }

    public function logout(): void {
        $this->current_user = null;
    }

    public function getCurrentUser() {
        return $this->current_user;
    }

    public function isLoggedIn(): bool {
        return $this->current_user !== null;
    }

    public function displayAllArticles(): void {
        foreach ($this->articles as $article) {
            echo
                $article->getId() . " - " .
                $article->getTitle() . " par " .
                $article->getAuteurName() . 
                "<br>\n";
        }
    }
}



$collection = Collection::getInstance();

$result = $collection->login('alice','pass123');
echo $result ? "Connexion alice OK \n" : "Échec connexion alice\n";

// Test 2: Connexion échouée
$result = $collection->login('alice', 'wrongpass');
echo !$result ? "Rejet mauvais mot de passe OK \n" : "Problème vérification\n";

// Test 3: Vérification état connexion
if ($collection->isLoggedIn()) {
    $user = $collection->getCurrentUser();
    echo "Utilisateur connecté: " . $user->getUsername() . "\n"; // Typo fixed: usernam -> getUsername()
}

// Test 4: Display Articles
echo "\n--- Liste des Articles ---\n";
$collection->displayAllArticles();

// Test 5: Déconnexion
$collection->logout();
echo !$collection->isLoggedIn() ? "Déconnexion OK\n" : "Problème déconnexion\n";

$user=new Admin(2, "ayoub", "ayoub@gmail.com","12345678");
$user->supprimer_utilisateurs(1,$collection->getTableUsers());
?>