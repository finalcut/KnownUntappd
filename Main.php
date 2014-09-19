<?php

    namespace IdnoPlugins\Untappd {

        class Main extends \Idno\Common\Plugin
        {

            function registerPages()
            {
                // Register the deauth URL
                \Idno\Core\site()->addPageHandler('Untappd/deauth', '\IdnoPlugins\Untappd\Pages\Deauth');
                // Register the callback URL
                \Idno\Core\site()->addPageHandler('Untappd/callback', '\IdnoPlugins\Untappd\Pages\Callback');
                // Register admin settings
                \Idno\Core\site()->addPageHandler('admin/Untappd', '\IdnoPlugins\Untappd\Pages\Admin');
                // Register settings page
                \Idno\Core\site()->addPageHandler('account/Untappd', '\IdnoPlugins\Untappd\Pages\Account');

                /** Template extensions */
                // Add menu items to account & administration screens
                \Idno\Core\site()->template()->extendTemplate('admin/menu/items', 'admin/Untappd/menu');
                \Idno\Core\site()->template()->extendTemplate('account/menu/items', 'account/Untappd/menu');
                \Idno\Core\site()->template()->extendTemplate('onboarding/connect/networks', 'onboarding/connect/Untappd');
            }

            function registerEventHooks()
            {

                \Idno\Core\site()->syndication()->registerService('Untappd', function () {
                    return $this->hasUntappd();
                }, ['place']);

                // Push checkins to Untappd
                \Idno\Core\site()->addEventHook('post/place/Untappd', function (\Idno\Core\Event $event) {
                    $object = $event->data()['object'];
                    if ($this->hasUntappd()) {
                        $fsObj = $this->connect();
                        /* @var \EpiUntappd $fsObj */
                        $name = $object->placename;
                        $ll   = $object->lat . ',' . $object->long;
                        if ($venues = $fsObj->get('/venues/search', ['ll' => $ll, 'query' => $name, 'limit' => 1, 'v' => '20131031'])) {
                            if (!empty($venues->response->venues) && is_array($venues->response->venues)) {
                                if (!empty($venues->response->venues[0])) {
                                    $item  = $venues->response->venues[0];
                                    $fs_id = $item->id;
                                    if (!empty($item->location)) {
                                        $object->lat  = $item->location->lat;
                                        $object->long = $item->location->lng;
                                        $object->name = $item->name;
                                        $object->save();
                                    }
                                    $shout = substr(strip_tags($object->body), 0, 140);
                                    if (empty($shout)) $shout = '';
                                    $result = $fsObj->post('/checkins/add', ['venueId' => $fs_id, 'shout' => $shout, 'v' => '20131031']);
                                    if (!empty($result->response)) {
                                        if ($json = $result) {
                                            if (!empty($json->response->checkin->id)) {
                                                $object->setPosseLink('Untappd', 'https://Untappd.com/forward/checkin/' . $json->response->checkin->id);
                                                $object->save();
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                });
            }

            /**
             * Can the current user use Untappd?
             * @return bool
             */
            function hasUntappd()
            {
                if (!empty(\Idno\Core\site()->session()->currentUser()->Untappd['access_token'])) {
                    return true;
                }

                return false;
            }

            /**
             * The URL to authenticate with the API
             * @return string
             */
            function getAuthURL()
            {

                $Untappd = $this;
                $login_url  = '';
                if (!$Untappd->hasUntappd()) {
                    if ($fs = $Untappd->connect()) {
                        $login_url = $fs->getAuthorizeUrl(\Idno\Core\site()->config()->url . 'Untappd/callback');
                    }
                }

                return $login_url;

            }

            /**
             * Connect to Untappd
             * @return bool|\Untappd
             */
            function connect()
            {
                if (!empty(\Idno\Core\site()->config()->Untappd)) {
                    $Untappd = new \EpiUntappd(\Idno\Core\site()->config()->Untappd['clientId'], \Idno\Core\site()->config()->Untappd['secret']);
                    if ($this->hasUntappd()) {
                        if ($user = \Idno\Core\site()->session()->currentUser()) {
                            $Untappd->setAccessToken($user->Untappd['access_token']);
                        }
                    }

                    return $Untappd;
                }

                return false;
            }

        }

    }

    namespace {
        require_once(dirname(__FILE__) . '/external/Untappd-async/EpiCurl.php');
        require_once(dirname(__FILE__) . '/external/Untappd-async/EpiUntappd.php');
        require_once(dirname(__FILE__) . '/external/Untappd-async/EpiSequence.php');
    }