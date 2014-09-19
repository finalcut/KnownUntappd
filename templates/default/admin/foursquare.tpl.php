<div class="row">

    <div class="span10 offset1">
        <h1>Untappd</h1>
        <?=$this->draw('admin/menu')?>
    </div>

</div>
<div class="row">
    <div class="span10 offset1">
        <form action="<?=\Idno\Core\site()->config()->getURL()?>admin/Untappd/" class="form-horizontal" method="post">
            <div class="control-group">
                <div class="controls">
                    <p>
                        To begin using Untappd, <a href="https://Untappd.com/developers/apps" target="_blank">create a new application in
                            the Untappd apps portal</a>.</p>
                    <p>
                        Set the redirect URL to be:<br />
                        <input type="text" class="span5" value="<?=\Idno\Core\site()->config()->url?>Untappd/callback" />
                    </p>
                    <p>
                        Once you've finished, fill in the details below. You can then <a href="<?=\Idno\Core\site()->config()->getURL()?>account/Untappd/">connect your Untappd account</a>.
                    </p>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="name">Client ID</label>
                <div class="controls">
                    <input type="text" id="name" placeholder="Client ID" class="span4" name="clientId" value="<?=htmlspecialchars(\Idno\Core\site()->config()->Untappd['clientId'])?>" >
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="name">Client secret</label>
                <div class="controls">
                    <input type="text" id="name" placeholder="Client secret" class="span4" name="secret" value="<?=htmlspecialchars(\Idno\Core\site()->config()->Untappd['secret'])?>" >
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <button type="submit" class="btn-primary">Save</button>
                </div>
            </div>
            <?= \Idno\Core\site()->actions()->signForm('/admin/Untappd/')?>
        </form>
    </div>
</div>
