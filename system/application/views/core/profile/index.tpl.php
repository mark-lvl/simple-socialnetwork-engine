<div class="profile_container">
    <div class="profile_user_box">
        <div class="user_item">
            <?= $lang['label_name']." : ".$user->first_name." ".$user->last_name ?>
        </div>
        <div class="user_item">
            <?= $lang['label_register_date']." : ".convert_number(fa_strftime("%d %B %Y", $user->registration_date . "")) ?>
        </div>
        <div class="user_item">
            <?= anchor('core/profile/edit', $lang['core_profile_edit']) ?>
        </div>
    </div>
    <div class="user_friends">
        <?php 
              if($friends)
              foreach($friends AS $f): ?>
            <?= anchor('core/profile/view/'.$this->encrypt->my_encode($f->id), $f->first_name." ".$f->last_name) ?><br/>
        <?php endforeach; ?>
    </div>
</div>
