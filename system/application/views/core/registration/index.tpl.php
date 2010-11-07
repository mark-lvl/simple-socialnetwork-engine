<div id="form_container">
    <?php echo form_open('core/registration/index'); ?>
        <div class="form_description">
            <h2><?= $lang['heading_registeration_form'] ?></h2>
        </div>
        <?php if(isset($message)): ?>
        <div class="error">
            <?= $message ?>
        </div>
        <?php endif; ?>
        <ul >
            <li>
                <label class="description"><?= $lang['label_first_name'] ?></label>
                <div>
                    <input name="first_name" class="element text medium" type="text" maxlength="255" value="<?php echo set_value('first_name'); ?>"/>
                </div>
                <?php echo form_error('first_name', '<div class="error">', '</div>'); ?>
            </li>
            <li>
                <label class="description"><?= $lang['label_last_name'] ?></label>
                <div>
                    <input name="last_name" class="element text medium" type="text" maxlength="255" value="<?php echo set_value('last_name'); ?>"/>
                </div>
                <?php echo form_error('last_name', '<div class="error">', '</div>'); ?>
            </li>
            <li>
                <label class="description"><?= $lang['label_gender'] ?></label>
                <span>
                    <label class="choice" for="element_5_1"><?= $lang['label_gender_man'] ?></label>
                    <input name="gender" class="element radio" type="radio" value="1" <?php echo set_radio('gender','1'); ?>/>
                    <label class="choice" for="gender"><?= $lang['label_gender_woman'] ?></label>
                    <input name="gender" class="element radio" type="radio" value="2" <?php echo set_radio('gender','2'); ?>/>
                </span>
                <?php echo form_error('gender', '<div class="error">', '</div>'); ?>
            </li>
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
            <li>
                <label class="description"><?= $lang['label_passconf'] ?></label>
                <div>
                    <input name="repassword" class="element text medium" type="password" maxlength="255" value="<?php echo set_value('repassword'); ?>"/>
                </div>
            </li>
            <li class="buttons">
                <input id="saveForm" class="button_text" type="submit" name="submit" value="<?= $lang['label_submit'] ?>" />
            </li>
        </ul>
    <div id="footer">
    </div>
</div>