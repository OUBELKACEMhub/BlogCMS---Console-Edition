<?php
    //class User  
class User { 
     protected int $id  ;
     protected string $username ;
     protected string $email ;
     protected  string $password ;
     protected string $role;
     public Article $articles=[];
     
    function __construct($id,$username,$email,$password,$role,$articles){
         $this->id=$id;
         $this->username=$username;
         $this->email=$email;
         $this->password=$password;
         $this->role=$role;
         $this->articles=$articles;
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
                                               // Class Moderateur
    class Moderateur extends User{
    public array  $categories=[];
     public array  $commantaires=[]
    function __construct($id,$username,$email,$password){
        User::__construct($id,$username,$email,$password,$role,$articles);
        $this->categories=$categories;
        $this->commantaires=$commantaires;
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
    
                                                                //  Class Auteur
    class Auteur extends User {       
        function __construct($id,$username,$email,$password,$role,$articles){
        __construct($id,$username,$email,$password,$role,$articles);
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


                                                                   //   class Editeur
    class Editeur extends Moderateur{

      
           function __construct($id,$username,$email,$password,$role,$articles){
         Moderateur::__construct($id,$username,$email,$password,$role,$articles);    
    }

}
    class Admin extends Moderateur{
      

           function __construct($id,$username,$email,$password,$role,$articles){
         Moderateur::__construct($id,$username,$email,$password,$role,$articles);    
    }   
    }

    public function cree_utilisateurs(User $user){
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

class Category
 {
    public string $name;
    public function __construct($name) {
        $this->name = $name;
    }
}

class commentaires 
{
      protected int $id;
      protected string $content;
      protected string $date_cr;
}



                                                   //class Article
class Article {
    private int $id;
    public string $title;
    public string $content;
    public string $status;
    public string $auteur;
    public Category $category=[];
    public Comments $comments=[];

    public function __construct($id, $title, $content, $status, $auteur, $category) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->status = $status;
        $this->auteur = $auteur;
        $this->category = $category;
        $this->comments = $comments;
    }

    public function getId() {
        return $this->id;
    }
}




$user1=(1, "ahmed", "ahmed@gmail.com", "123456", "user",$article[comments[],comment_users[],categorie[]]);








































?> -->