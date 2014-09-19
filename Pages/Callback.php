<?php

    /**
     * Untappd callback
     */

    namespace IdnoPlugins\Untappd\Pages {

        /**
         * Default class to serve the Facebook callback
         */
        class Callback extends \Idno\Common\Page
        {

            function get()
            {
                $this->gatekeeper(); // Logged-in users only
                if ($Untappd = \Idno\Core\site()->plugins()->get('Untappd')) {
                    $fsObj = $Untappd->connect();
                    $token = $fsObj->getAccessToken($this->getInput('code'), \Idno\Core\site()->config()->url . 'Untappd/callback');
                    $fsObj->setAccessToken($token->access_token);
                    $user = \Idno\Core\site()->session()->currentUser();
                    $user->Untappd = ['access_token' => $token->access_token];
                    $user->save();
                    \Idno\Core\site()->session()->addMessage('Your Untappd account was connected.');
                }
                if (!empty($_SESSION['onboarding_passthrough'])) {
                    unset($_SESSION['onboarding_passthrough']);
                    $this->forward(\Idno\Core\site()->config()->getURL() . 'begin/connect-forwarder');
                }
                $this->forward(\Idno\Core\site()->config()->getURL() . 'account/Untappd/');
            }

        }

    }