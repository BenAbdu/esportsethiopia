<?php

    namespace App\Controllers;

    use App\Functions\Register;
    use App\Functions\SantimPay;
   
    class PublicController extends Controller {

        public function landing($request,$response){

            return $this->c->view->render($response,'public/landing.twig');
        }

        public function ourTeam($request,$response){

            return $this->c->view->render($response,'public/ourTeam.twig');
        }

        public function contribute($request,$response){

            return $this->c->view->render($response,'public/contribute.twig');
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
            $PRIVATE_KEY_IN_PEM = "-----BEGIN EC PRIVATE KEY-----\nMHcCAQEEIJ2YRyeLjQHQNY8b345KxZIeiGePs9GsIpDA2C8W8SZ8oAoGCCqGSM49\nAwEHoUQDQgAE4Wn+ArAufALiTshTFhqBam6LmeUBGb6u2Y4IBmXN5vPyvnqny5JE\n5GSXcI9l/bG/9V1kHn1cy7b7tYwIZhT4pA==\n-----END EC PRIVATE KEY-----\n";
            $GATEWAY_MERCHANT_ID = 'aa2c2aeb-5593-4976-be3e-50fefaac1afe';

            $santimPay = new SantimPay($GATEWAY_MERCHANT_ID, $SANTIMPAY_GATEWAY_TOKEN, $PRIVATE_KEY_IN_PEM);
            
            // client side pages to redirect user to after payment is completed/failed
            $successRedirectUrl = 'https://esportsethiopia.com/register/success';
            $failureRedirectUrl = 'https://esportsethiopia.com/register/failed';

            // backend url to receive a status update (webhook)
            $notifyUrl = 'https://esportsethiopia.com/register/accept';

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

        public function registerAccept($request,$response){

            $callback = $request->getParams();

            $status = $callback->Status;
            $sessionID = $callback->thirdPartyId;

            if($status == "COMPLETED"){
                $channel = $callback->paymentVia;

                $register = new Register($this->c->db);
                $register->updatePaid($sessionID,$channel);

            }
            else {
                //Nothing
            }

        }
        

        public function registerFailed($request,$response){

            return $this->c->view->render($response,'public/registerFailed.twig');
        }

        public function registerSuccess($request,$response){

            return $this->c->view->render($response,'public/registerSuccess.twig');
        }


    }


?>