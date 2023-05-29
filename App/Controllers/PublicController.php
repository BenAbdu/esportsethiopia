<?php

    namespace App\Controllers;

    use App\Functions\Register;
   
    class PublicController extends Controller {

        public function landing($request,$response){

            return $this->c->view->render($response,'public/landing.twig');
        }





        public function register($request,$response){

            return $this->c->view->render($response,'public/register.twig');
        }

        public function registerUser($request,$response){

            $name = $request->getParam('name');
            $gamerTag = strtolower(str_replace(' ', '', $request->getParam('gamerTag')));
            $gameID = $request->getParam('gameID');

            $register = new Register($this->c->db);
            $register->create($name, $gamerTag, $gameID);

        }
        

    }


?>