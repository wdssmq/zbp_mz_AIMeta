<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
$action = 'root';
if (!$zbp->CheckRights($action)) {
  $zbp->ShowError(6);
  die();
}
if (!$zbp->CheckPlugin('mz_AIMeta')) {
  $zbp->ShowError(48);
  die();
}

if (count($_POST) > 0) {
  CheckIsRefererValid();
}

$blogtitle = '「水水」AI 创作等级';
require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';
?>
<div id="divMain">
  <div class="divHeader"><?php echo $blogtitle; ?></div>
  <div class="SubMenu">
  </div>
  <div id="divMain2">
    <p>暂时没有配置项</p>
    <hr>
    <p>拒绝自欺欺人！给博客文章加上 AI 创作等级标识（附完整 PHP+CSS 代码） - 秋风于渭水</p>
    <p><a href="https://www.tjsky.net/tutorial/1397" target="_blank" title="拒绝自欺欺人！给博客文章加上 AI 创作等级标识 (附完整 PHP+CSS 代码） - 秋风于渭水">https://www.tjsky.net/tutorial/1397</a></p>
  </div>
</div>

<?php
require $blogpath . 'zb_system/admin/admin_footer.php';
RunTime();
?>
