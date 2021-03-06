<?php
/**
 * DouPHP
 * --------------------------------------------------------------------------------------------------
 * 版权所有 2013-2015 漳州豆壳网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.douco.com
 * --------------------------------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在遵守授权协议前提下对程序代码进行修改和使用；不允许对程序代码以任何形式任何目的的再发布。
 * 授权协议：http://www.douco.com/license.html
 * --------------------------------------------------------------------------------------------------
 * Author: DouCo
 * Release Date: 2015-10-16
 */
define('IN_DOUCO', true);

require (dirname(__FILE__) . '/include/init.php');

// rec操作项的初始化
$rec = $check->is_rec($_REQUEST['rec']) ? $_REQUEST['rec'] : 'default';

// 赋值给模板
$smarty->assign('rec', $rec);
$smarty->assign('cur', 'index_' . $rec);

/**
 * +----------------------------------------------------------
 * 系统信息
 * +----------------------------------------------------------
 */
if ($rec == 'default') {
    $sys_info['os'] = PHP_OS;
    $sys_info['ip'] = $_SERVER['SERVER_ADDR'];
    $sys_info['web_server'] = $_SERVER['SERVER_SOFTWARE'];
    $sys_info['php_ver'] = PHP_VERSION;
    $sys_info['mysql_ver'] = $dou->version();
    $sys_info['gd'] = extension_loaded("gd") ? $_LANG['yes'] : $_LANG['no'];
    $sys_info['charset'] = strtoupper(DOU_CHARSET);
    $sys_info['build_date'] = date("Y-m-d", $_CFG['build_date']);
    $update_date = unserialize($_CFG['update_date']);
    $sys_info['update'] = $update_date['system']['update'];
    $sys_info['patch'] = $update_date['system']['patch'];
    $sys_info['logo'] = ROOT_URL . 'theme/' . $_CFG['site_theme'] . '/images/' . $_CFG['site_logo'];
    $sys_info['max_filesize'] = ini_get('upload_max_filesize');
    $sys_info['num_page'] = $dou->num_rows($dou->query("SELECT * FROM " . $dou->table('page')));
    $sys_info['num_product'] = $dou->num_rows($dou->query("SELECT * FROM " . $dou->table('product')));
    $sys_info['num_article'] = $dou->num_rows($dou->query("SELECT * FROM " . $dou->table('article')));
    
    // 提示应该被删除的目录未被删除
    if ($dou->dir_status(ROOT_PATH . 'install') != 'no_exist') $warning[] = $_LANG['warning_install_exists'];
    if ($dou->dir_status(ROOT_PATH . 'upgrade') != 'no_exist') $warning[] = $_LANG['warning_upgrade_exists'];
    if ($extend == 'on') $warning[] = $_LANG['warning_extend_exists'];
    
    // 写入目录监测信息
    $sys_info['folder_exists'] = $warning;
    
    // 赋值给模板
    $smarty->assign('dou_api', $dou->dou_api());
    $smarty->assign('cur', 'index');
    $smarty->assign('page_list', $dou->get_page_nolevel());
    $smarty->assign('sys_info', $sys_info);
    $smarty->assign("log_list", $dou->get_admin_log($_SESSION[DOU_ID]['user_id'], 4));
    $smarty->assign('localsite', $dou->dou_localsite());
    
    $smarty->display('index.htm');
} 

/**
 * +----------------------------------------------------------
 * 清除缓存及已编译模板
 * +----------------------------------------------------------
 */
elseif ($rec == 'clear_cache') {
    $dou->dou_clear_cache(ROOT_PATH . 'cache');
    $dou->dou_msg($_LANG['clear_cache_success']);
}

?>