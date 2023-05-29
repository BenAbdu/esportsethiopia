<?php 

    use App\Controllers\PublicController;

    //Public 
    $app->get('/', PublicController::class.':landing')->setName('landing');
    
    //Register
    $app->get('/register', PublicController::class.':register')->setName('register');
    $app->post('/register', PublicController::class.':registerUser')->setName('registerUser');

?>