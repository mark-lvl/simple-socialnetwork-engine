<style>
  #centerContainer {
    background: url(<?= $base_img ?>profile/search_bg.gif) no-repeat top center;
    height: 395px;
    width: 464px;
    display: block;
    margin-top: 2px;
}
</style>
<script>
function searchPager(page,cnt)
{
    var params = {};

    if(page == 0)
        params['page'] = $("#page").val();
    else
        params['page'] = page;

    params['cnt'] = cnt;
    params['filter'] = '<?= $parse ?>';
    params['pagination'] = 1;
    ajax_request('#searchAjaxHolder','<?= base_url() ?><?= $controllerName ?>/find',params);
    return false;
}
</script>
<div id="centerContainer">
<div id="searchContainer">
    <div id="searchHeader">
        <span class="title"><?= $lang['search'] ?></span>
        <span class="body"><?= str_replace(array('__COUNT__','__PARSE__'), array($cnt,$parse), $lang['searchResult']) ?></span>
    </div>
    <div id="searchAjaxHolder">
        <?php $this->load->view("$controllerName/search-inner.tpl.php", $items); ?>
    </div>
</div>

</div>