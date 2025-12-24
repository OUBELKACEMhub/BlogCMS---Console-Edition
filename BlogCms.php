<?php  
class User { 
     protected int $id  ;
     protected string $username ;
     protected string $email ;
     protected  string $password ;
     protected string $role;
     
    function __construct($id,$username,$email,$password,$role){
         $this->id=$id;
         $this->username=$username;
         $this->email=$email;
         $this->password=$password;
         $this->role=$role;
    }
    
    public function login($username1,$password1):boolean
    {
       ( $this->username==$username1 && $this->password==$password1) ? true : false ;
    }

   public function logout(){
    echo "\n Déconnexion en cours...\n";
   }



   
    public function afficherUsers() {
        return $this->username . ' ' . $this->email .' '. $this->password .' '.$this->role."<br>";
    }

    public function estAuteur() : bool {
        if($this->role=="auteur")
        return true;
        return false;
    }

     public function estAdmin() : bool {
        if($this->role=="admin")
        return true;
        return false;
    }

     public function estEditeur() : bool {
        if($this->role=="editeur")
        return true;
        return false;
    }

 
    public function lireArticles($articles) {
    foreach ($articles as $article) { 
        echo "
        title   : {$article['title']}\n
        content : {$article['content']}\n
        status  : {$article['status']}\n
        auteur  : {$article['auteur']}\n
        category: {$article['category']}\n\n
        ";
  }
}


    public function createCommantaires(Commentaire  $commente){
         array_push($commentaires,$commente);
    }
    
   public function getUsername(){
     return $this->username;
   }
   public function getByid(){
     return $this->id;
   }

   public function getEmail(){
    return $this->email;
   }

    public function getPassword(){
    return $this->password;
   }

    
    }

    class Moderateur extends User{
    public array $articles=[];
    public array  $categories=[];
    function __construct($id,$username,$email,$password){
        User::__construct($id, $username, $email, $password);
        $this->articles=$articles;
        $this->categories=$categories;   
    }

public function  AjouterArticle(Article $Aticle){       
        if( $Aticle){
            array_push($drafts,$articles);
        }     
    }

  

    public function  createCategory(Category  $Category) : void{
       array_push($categories,$category);
    }


//supprimer les articles
    public function  SupprimerArticle(){
          for( $art=0;$art<count($articles);$art++){
            if($article[$art]->getByid()==$this->id){
             $unset($article[$art]);
             exit;
            }        
    }

//modifier les articles
      public function  ModifierArticleById(Article $newaAticle){   
           foreach($articles as $article){
            if($article->getByid()==$this->id){
             $articl=$newArticle;
             exit;
            }
        }
            echo "article inrouvable!!";

    }
    }}
    

    class Auteur extends User {
        public $articles=[];        
        function __construct($id,$username,$email,$password,$role,$articles){
         User::__construct($id, $username, $email, $password, $role);
         $this->articles = $articles;   
    }


    public function createArticle(Article  $article) : void {
       if($article->getByid()==$this->id)
       array_push($articles,$article);
      else
      echo "impossible de cree cette article";
    }

      public function modifierArticleById(Article $newArticle) :bool {
    foreach ($this->articles as $index => $article) {
        if ($article->getId() === $newArticle->getId()) {
            $this->articles[$index] = $newArticle;
            return true;
        }
        return false;
    }

    echo "Article introuvable !!";
    return false;
}

    }



    class Editeur extends Moderateur{

      
           function __construct($id,$username,$email,$password,$role){
         Moderateur::__construct($id, $username, $email, $password, $role);    
    }

}
    class Admin extends Moderateur{
      

           function __construct($id,$username,$email,$password){
         Moderateur::__construct($id, $username, $email, $password, $role);    
    }

    public function cree_utilisateurs($user){
        array_push($users,$user);
    }


    public function supprimer_utilisateurs($user){
        foreach($i=0;$i<count($users);$i++){
            if($users[$i]==$user){
                unset($users[$i]);
            }
        }
    }

}


class Artile{
    
}





$users = [
    new User(1, "ahmed", "ahmed@gmail.com", "123456", "user"),
    new User(2, "fatima", "fatima@gmail.com", "fatima@2024", "auteur"),
    new User(3, "admin01", "admin@site.com", "admin123", "admin")
];





































?> -->