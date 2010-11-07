<div id="form_container">
    <?php echo form_open('core/registration/login'); ?>
        <div class="form_description">
            <h2><?= $lang['heading_login_form'] ?></h2>
        </div>
        <?php if(isset($message)): ?>
        <div class="error">
            <?= $message ?>
        </div>
        <?php endif; ?>
        <ul >
            <li>
                <label class="description"><?= $lang['label_email'] ?></label>
                <div>
                    <input name="email" class="element text medium" type="text" maxlength="255" value="<?php echo set_value('email'); ?>"/>
                </div>
                <?php echo form_error('email', '<div class="error">', '</div>'); ?>
            </li>
            <li>
                <label class="description"><?= $lang['label_password'] ?></label>
                <div>
                    <input name="password" class="element text medium" type="password" maxlength="255" value="<?php echo set_value('password'); ?>"/>
                </div>
                <?php echo form_error('password', '<div class="error">', '</div>'); ?>
            </li>
            <li class="buttons">
                <input id="saveForm" class="button_text" type="submit" name="submit" value="<?= $lang['label_submit'] ?>" />
            </li>
        </ul>
    <div id="footer">
    </div>
</div>