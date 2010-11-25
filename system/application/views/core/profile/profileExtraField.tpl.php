<li>
    <label class="description"><?= $lang['label_gender'] ?></label>
    <span>
        <label class="choice" for="element_5_1"><?= $lang['label_gender_man'] ?></label>
        <input name="gender" class="element radio" type="radio" value="1" <?php set_radio('gender', '1', $user->sex); ?>>
        <label class="choice" for="gender"><?= $lang['label_gender_woman'] ?></label>
        <input name="gender" class="element radio" type="radio" value="2" <?php echo set_radio('gender','2', $user->sex); ?>>
    </span>
    <?php echo form_error('gender', '<div class="error">', '</div>'); ?>
</li>
<li>
    <label class="description"><?= $lang['label_city'] ?></label>
    <div>
        <input name="city" class="element text medium" type="text" maxlength="255" value="<?php echo set_value('city', $user->city); ?>" />
    </div>
    <?php echo form_error('city', '<div class="error">', '</div>'); ?>
</li>
