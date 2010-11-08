<div class="profile_container">
    <div class="profile_user_box">
        <div class="user_item">
            <?= $lang['label_name']." : ".$partner->first_name." ".$partner->last_name ?>
        </div>
        <div class="user_item">
            <?= $lang['label_register_date']." : ".convert_number(fa_strftime("%d %B %Y", $partner->registration_date . "")) ?>
        </div>
    </div>
    <div class="user_friends">
        <?php foreach($friends AS $f): ?>
            <?= anchor('core/profile/view/'.base64_encode($f->id), $f->first_name." ".$f->last_name) ?><br/>
        <?php endforeach; ?>
    </div>
</div>
