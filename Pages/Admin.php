<?php

    /**
     * Untappd admin
     */

    namespace IdnoPlugins\Untappd\Pages {

        /**
         * Default class to serve Facebook settings in administration
         */
        class Admin extends \Idno\Common\Page
        {

            function getContent()
            {
                $this->adminGatekeeper(); // Admins only
                $t = \Idno\Core\site()->template();
                $body = $t->draw('admin/Untappd');
                $t->__(['title' => 'Untappd', 'body' => $body])->drawPage();
            }

            function postContent() {
                $this->adminGatekeeper(); // Admins only
                $clientId = $this->getInput('clientId');
                $secret = $this->getInput('secret');
                \Idno\Core\site()->config->config['Untappd'] = [
                    'clientId' => $clientId,
                    'secret' => $secret
                ];
                \Idno\Core\site()->config()->save();
                \Idno\Core\site()->session()->addMessage('Your Untappd application details were saved.');
                $this->forward('/admin/Untappd/');
            }

        }

    }