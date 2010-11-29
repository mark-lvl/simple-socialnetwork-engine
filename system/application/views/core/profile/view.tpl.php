<script>
function addToFriendConfirm()
{
    Boxy.confirm('MARK', addToFriend,{title:'TITLE'});
}
function addToFriend()
{
    var params = {};
    params['id'] = id;
    ajax_request('#ajaxHolder','<?= base_url() ?>profile/addToFriend',params);
}
</script>

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
        <?php
        if(is_array($friends))
        foreach($friends AS $f): ?>
            <?= anchor('core/profile/view/'.$encryption->encrypt($f->id), $f->first_name." ".$f->last_name) ?><br/>
        <?php endforeach; ?>
    </div>
    <div class="social_action">
        <?php if($relation_status == 'waiting'): ?>
            <?= $lang['core_social_wait_for_accept'] ?>
        <?php elseif($relation_status == 'waitForMe'): ?>
            <?= $lang['core_social_wait_for_accept_by_me'] ?>
        <?php elseif($relation_status == 'related'): ?>
            <?= $lang['core_social_related'] ?>
        <?php elseif($relation_status == 'reject'): ?>
            <?= $lang['core_social_rejected_request'] ?>
        <?php elseif($relation_status == 'request'): ?>
            <?= anchor('',
                $lang['core_social_request_friend'],
                array('onclick'=>"addToFriendConfirm();return false;"));
            ?>
        <?php endif; ?>
    </div>
</div>