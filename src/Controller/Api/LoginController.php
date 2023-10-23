<?php 

namespace App\Controller\Api ;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseCookieValueSame; 

class LoginController extends  AbstractController
{
    public function ApiLogin(){
        
        $utilisateurs = $this->getUser();

        $userData = [
            'user' => '',

        ];

        return $this->json($userData,200,[],[]);
    }
}