<?php

    if ($Untappd = \Idno\Core\site()->plugins()->get('Untappd')) {
        if (empty(\Idno\Core\site()->session()->currentUser()->Untappd)) {
            $login_url = $Untappd->getAuthURL();
        } else {
            $login_url = \Idno\Core\site()->config()->getURL() . 'Untappd/deauth';
        }
    }

?>
<div class="social">
    <a href="<?= $login_url ?>" class="connect fsqr <?php

        if (!empty(\Idno\Core\site()->session()->currentUser()->Untappd['access_token'])) {
            echo 'connected';
        }

    ?>" target="_top">Untappd<?php

            if (!empty(\Idno\Core\site()->session()->currentUser()->Untappd['access_token'])) {
                echo ' - connected!';
            }

        ?></a>
    <label class="control-label">Share locations to Untappd.</label>
</div>