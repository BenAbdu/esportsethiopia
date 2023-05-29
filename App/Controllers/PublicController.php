<?php

    namespace App\Controllers;

    use App\Functions\Register;
    use App\Functions\SantimPay;
   
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
            $phone = $request->getParam('phone');

            $register = new Register($this->c->db);
            $sessionID = $register->create($name, $gamerTag, $gameID, $phone);

            $SANTIMPAY_GATEWAY_TOKEN = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJwaG9uZU51bWJlciI6IisyNTE5MTAxMDEwMTAiLCJ3YWxsZXRJZCI6IjJkY2I0MzE0LTg0MTAtNDQ1YS05YjVlLTczNWE5YjE0OTZkZCIsInVzZXJJZCI6IjZkMjhhZmFiLTkzOWUtNGZjMC04Mzg1LTA4M2I2Zjc1ZTQwYSIsImRldmljZUlkIjoic2FtcG1tazIiLCJleHAiOjE2ODUwNzg2Mjd9.tJkcBi5FiSv9HDS1QLj0SsRxvvVbRFDaYHiVyx6no7w';
            $PRIVATE_KEY_IN_PEM = "-----BEGIN EC PRIVATE KEY-----\nMHcCAQEEIPDaoRtwp0oX6X8FTRLDeoHfBFqrePqR2kCjQ68RWPjNoAoGCCqGSM49\nAwEHoUQDQgAEhLUSwugz8HplU8X+xUrJIrv6dRGfZ6VhjVxoUZLp+5kg8za/l8ft\nDyMIPiowQvVRp8EN4fII3gd9RGchfdocFA==\n-----END EC PRIVATE KEY-----\n";
            $GATEWAY_MERCHANT_ID = '9e2dab64-e2bb-4837-9b85-d855dd878d2b';

            $santimPay = new SantimPay($GATEWAY_MERCHANT_ID, $SANTIMPAY_GATEWAY_TOKEN, $PRIVATE_KEY_IN_PEM);

            // client side pages to redirect user to after payment is completed/failed
            $successRedirectUrl = 'http://localhost/esportsethiopia/public_html/register/response/success';
            $failureRedirectUrl = 'http://localhost/esportsethiopia/public_html/register/response/failed';

            // backend url to receive a status update (webhook)
            $notifyUrl = 'http://localhost/esportsethiopia/public_html/register/response/webhook';

            // custom ID used by merchant to identify the payment
            $id = $sessionID;

            $urlData = $santimPay->generatePaymentURL($id, 1, 'Esports Registeration Fee', $successRedirectUrl, $failureRedirectUrl, $notifyUrl);

            $data = json_decode($urlData, true);
            $url = $data['url'];

            $data = array(
                "url" => $url
            );
            
            return $this->c->view->render($response,"public/redirect.twig",compact('data'));
        }
        

    }


?>