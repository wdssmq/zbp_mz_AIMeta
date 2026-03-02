<?php
RegisterPlugin("mz_AIMeta", "ActivePlugin_mz_AIMeta");

function ActivePlugin_mz_AIMeta()
{
  // 后台编辑输出表单及相关资源
  Add_Filter_Plugin('Filter_Plugin_Edit_Response3', 'mz_AIMeta_AddSelect');
  Add_Filter_Plugin('Filter_Plugin_Edit_Begin', 'mz_AIMeta_EchoCSS');
  Add_Filter_Plugin('Filter_Plugin_Edit_End', 'mz_AIMeta_EchoJS');
  // 保存文章时处理 AI 声明数据
  Add_Filter_Plugin('Filter_Plugin_PostArticle_Core', 'mz_AIMeta_Post');
  // 前端显示声明
  Add_Filter_Plugin('Filter_Plugin_ViewPost_Template', 'mz_AIMeta_EchoBadge');
}

/**
 * 本插件灵感及部分代码来源于以下文章，特此致谢：
 * ↑ 上边这行说明的 AI 等级为 1 ← 这行是 0
 * 拒绝自欺欺人！给博客文章加上 AI 创作等级标识 （附完整 PHP+CSS 代码） - 秋风于渭水
 * https://www.tjsky.net/tutorial/1397
 */

function mz_AIMeta_AddSelect()
{
  global $article;
  $level = $article->Metas->AILevel ? $article->Metas->AILevel : '0';
?>
  <div id="mz_AIMeta_box" class="editmod">
    <label for="cmbPostAILevel" class="editinputname">AI 声明</label>
    <select class="edit" size="1" name="AILevel" id="cmbPostAILevel">
      <option value="0" <?php mz_AIMeta_Check($level, '0'); ?>>🧠 AI 0 - 独立个体</option>
      <option value="1" <?php mz_AIMeta_Check($level, '1'); ?>>🪄 AI 1 - 灵感辅助</option>
      <option value="2" <?php mz_AIMeta_Check($level, '2'); ?>>🤝 AI 2 - 人机合作</option>
      <option value="3" <?php mz_AIMeta_Check($level, '3'); ?>>🤖 AI 3 - 硅基生成</option>
    </select>
  </div>
<?php
}

function mz_AIMeta_EchoCSS()
{
  global $article;
  echo '<link rel="stylesheet" href="' . mz_AIMeta_Path("css", 'host') . '">';
}

function mz_AIMeta_EchoJS()
{
  global $article;
  echo '<script src="' . mz_AIMeta_Path("script", 'host') . '"></script>';
}

function mz_AIMeta_Post(&$article)
{
  $level = GetVars('AILevel', 'POST') ? GetVars('AILevel', 'POST') : '0';
  $article->Metas->AILevel = $level;
}

// 文章内容接口
function mz_AIMeta_EchoBadge(&$template)
{
  $article = $template->GetTags('article');
  if (!$article) {
    return;
  }
  $level = $article->Metas->AILevel ? $article->Metas->AILevel : '0';
  $ai_info = mz_AIMeta_GetLevelInfo($level);
  $badgeHTML = sprintf(
    '<div class="mz-ai-meta %s"><span class="icon">%s</span> <span class="label">%s</span> - <span class="desc">%s</span></div>',
    'ai-' . $ai_info['level'],
    $ai_info['icon'],
    $ai_info['label'],
    $ai_info['desc']
  );
  // 将徽章插入到文章内容的开头
  $article->Content = $badgeHTML . $article->Content;
  // $header = $template->GetTags('header');
  // $header .= '<link rel="stylesheet" href="' . mz_AIMeta_Path("css", 'host') . '">';
  global $zbp;
  $style = mz_AIMeta_Path("css", 'host');
  $zbp->header .= "<link rel=\"stylesheet\" href=\"{$style}\">";
}


// ----------------------

// 判断 AI 创作声明
function mz_AIMeta_Check($level, $num)
{
  if ($level === $num) {
    echo 'selected';
  } else {
    echo '';
  }
}

// 获取 AI 创作等级信息
function mz_AIMeta_GetLevelInfo($level)
{


  $data = [
    '0' => ['icon' => '🧠', 'label' => '独立个体', 'desc' => '本文完全由人类的大脑完成'],
    '1' => ['icon' => '🪄', 'label' => '灵感辅助', 'desc' => '本文由人类主导撰写，AI 仅用于资料查询或辅助润色'],
    '2' => ['icon' => '🤝', 'label' => '人机合作', 'desc' => '本文人类和 AI 的贡献基本五五开，合作探讨完成'],
    '3' => ['icon' => '🤖', 'label' => '硅基生成', 'desc' => '本文绝大部分内容都由 AI 生成'],
  ];

  $current = $data[$level];

  return [
    'level' => $level,
    'icon' => $current['icon'],
    'label' => $current['label'],
    'desc' => $current['desc'],
  ];
}

function mz_AIMeta_Path($file, $t = 'path')
{
  global $zbp;
  $result = $zbp->$t . 'zb_users/plugin/mz_AIMeta/';
  switch ($file) {
    case 'script':
      return $result . 'var/plugin.js';
      break;
    case 'css':
      return $result . 'var/style.css';
      break;
    case 'usr':
      return $result . 'usr/';
      break;
    case 'var':
      return $result . 'var/';
      break;
    case 'main':
      return $result . 'main.php';
      break;
    default:
      return $result . $file;
  }
}


function InstallPlugin_mz_AIMeta() {}

function UninstallPlugin_mz_AIMeta() {}
