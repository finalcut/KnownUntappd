<?php

    /**
     * Facebook pages
     */

    namespace IdnoPlugins\Untappd\Pages {

        /**
         * Default class to serve Facebook-related account settings
         */
        class Account extends \Idno\Common\Page
        {

            function getContent()
            {
                $this->gatekeeper(); // Logged-in users only
                $login_url = '';
                if ($Untappd = \Idno\Core\site()->plugins()->get('Untappd')) {
                    $login_url = $Untappd->getAuthURL();
                }
                $t = \Idno\Core\site()->template();
                $body = $t->__(['login_url' => $login_url])->draw('account/Untappd');
                $t->__(['title' => 'Untappd', 'body' => $body])->drawPage();
            }

            function postContent() {
                $this->gatekeeper(); // Logged-in users only
                if (($this->getInput('remove'))) {
                    $user = \Idno\Core\site()->session()->currentUser();
                    $user->Untappd = [];
                    $user->save();
                    \Idno\Core\site()->session()->addMessage('Your Untappd settings have been removed from your account.');
                }
                $this->forward('/account/Untappd/');
            }

        }

    }