<?php 

    use App\Controllers\PublicController;

    //Public 
    $app->get('/', PublicController::class.':landing')->setName('landing');
    
    $app->get('/contribute', PublicController::class.':contribute')->setName('contribute');
    $app->get('/ourTeam', PublicController::class.':ourTeam')->setName('ourTeam');

    
    //Register
    $app->get('/register', PublicController::class.':register')->setName('register');
    $app->post('/register', PublicController::class.':registerUser')->setName('registerUser');
    $app->post('/register/accept', PublicController::class.':registerAccept')->setName('registerAccept');

    $app->get('/register/failed', PublicController::class.':registerFailed')->setName('registerFailed');
    $app->get('/register/success', PublicController::class.':registerSuccess')->setName('registerSuccess');



?>