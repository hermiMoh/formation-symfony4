<?php
    
namespace App\Controller ;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  
   

   class HomeController extends AbstractController {

    /**
     * Display a page for say Hello 
     *@Route("/hello/{name}/age/{age}", name ="hello")
     *@Route("/hello", name = "hello_base")
     *@Route("/hello/{name}", name="hello_name")
     */
      public function hello($name = "Empty", $age=0)  {
          return $this->render(
                'hello.html.twig',
                [
                    'name'=>$name,
                    'age'=>$age
                ]
          );
      }
        /**  
         *@Route("/", name="homepage")
         */
        public function home()  {
            $names=  ["Med"=>36, "Yasmine"=>9, "Najla"=>32, "Fatma"=>5, "Sarra"=>3] ;
            return $this->render(
                'home.html.twig',
                ['title' => "Hello World",
                'age' => 12,
                'tab' => $names
                ]
            );
        }
   }
?>