<!doctype html>
<html lang="en" style="overflow: hidden;">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Responsive Bootstrap4 Dashboard Template">
        <meta name="author" content="ParkerThemes">
        <link rel="shortcut icon" href="./public/index/img/fav.png">
        <title>{$system_config['name']}</title>
        <link rel="stylesheet" href="./public/index/css/bootstrap.min.css">
        <link rel="stylesheet" href="./public/index/fonts/style.css">
        <link rel="stylesheet" href="./public/index/css/main.css">
        <link rel="stylesheet" href="./public/index/vendor/megamenu/css/megamenu.css">
        <link rel="stylesheet" href="./public/index/vendor/search-filter/search-filter.css">
        <link rel="stylesheet" href="./public/index/vendor/search-filter/custom-search-filter.css">
        <script src="./public/index/js/jquery.min.js"></script>
        <script src="./public/index/js/bootstrap.bundle.min.js"></script>
        <script src="./public/index/js/modernizr.js"></script>
        <script src="./public/index/js/moment.js"></script>
        <script src="./public/layer/layer.js"></script>
        <script>
            var watermark_username = "账户：{$session_username}"
        </script>
        <style>
            table.dataTable td .actions{
                display: -webkit-inline-box !important;
            }
        </style>
    </head>
    <body>
        <div class="page-wrapper">
            <nav class="sidebar-wrapper">
                <div class="sidebar-tabs">
                    <div class="nav" role="tablist" aria-orientation="vertical">
                        <a href="#" class="logo">
                            <img src="./public/index/img/logo.jpg" alt="Uni Pro Admin">
                        </a>
                        <a class="nav-link {if $url_path == "IndexControllers"}active{/if}" id="home-tab" data-bs-toggle="tab" href="#tab-home" role="tab" aria-controls="tab-home" aria-selected="true" onclick="javascript:window.location.href='{$menu['home']}'">
                            <i class="icon-home2"></i>
                            <span class="nav-link-text">首页</span>
                        </a>
                        <a class="nav-link {if $url_path == "ProductsControllers"}active{/if}" id="product-tab" data-bs-toggle="tab" href="#tab-product" role="tab" aria-controls="tab-product" aria-selected="false"  onclick="javascript:window.location.href='{$menu['products_index']}'">
                            <i class="icon-layers2"></i>
                            <span class="nav-link-text">项目</span>
                        </a>
                        {if $user_info['id'] == "1"}
                            <a class="nav-link {if $url_path == "UserControllers"}active{/if}" id="authentication-tab" data-bs-toggle="tab" href="#tab-authentication" role="tab" aria-controls="tab-authentication" aria-selected="false"  onclick="javascript:window.location.href='{$menu['user_member']}'">
                                <i class="icon-users"></i>
                                <span class="nav-link-text">用户管理</span>
                            </a>
                        {else}
                            <a class="nav-link {if $url_path == "UserControllers"}active{/if}" id="authentication-tab" data-bs-toggle="tab" href="#tab-authentication" role="tab" aria-controls="tab-authentication" aria-selected="false"  onclick="javascript:window.location.href='{$menu['user_index']}'">
                                <i class="icon-user1"></i>
                                <span class="nav-link-text">个人中心</span>
                            </a>
                        {/if}
                        {if $user_info['id'] == "1"}
                            <a class="nav-link {if $url_path == "LogControllers" or $url_path == "SetupControllers"}active{/if}" id="log-tab" data-bs-toggle="tab" href="#tab-log" role="tab" aria-controls="tab-log" aria-selected="false" onclick="javascript:window.location.href='{$menu['setup_index']}'">
                                <i class="icon-settings1"></i>
                                <span class="nav-link-text">网站管理</span>
                            </a>
                        {/if}
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade {if $url_path == "IndexControllers"}show active{/if}" id="tab-home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="tab-pane-header">
                                首页
                            </div>
                            <div class="sidebarMenuScroll">
                                <div class="sidebar-menu">
                                    <ul>
                                        <li>
                                            <a href="{$menu['home']}" {if $url_path == "IndexControllers" && $url_path_action == "index"} class="current-page" {/if}>首页</a>
                                        </li>
                                        <li>
                                            <a href="{$menu['index_about_us']}" {if $url_path == "IndexControllers" && $url_path_action == "about_us"} class="current-page" {/if}>关于我们</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade {if ($url_path == "ProductsControllers" || $url_path == "DocxControllers")}show active{/if}" id="tab-product" role="tabpanel" aria-labelledby="product-tab">
                            <div class="tab-pane-header">
                                项目
                            </div>
                            <div class="sidebarMenuScroll">
                                <div class="sidebar-menu">
                                    <ul>
                                        <li>
                                            <a href="{$menu['products_index']}" {if $url_path == "ProductsControllers" && ($url_path_action == "index" || $url_path_action == "add_index" || $url_path_action == "edit_index" || $url_path_action == "repair_index" || $url_path_action == "repair_view_index")} class="current-page" {/if}>漏洞列表</a>
                                        </li>
                                        {if $user_info['id'] == "1"}
                                            <li>
                                                <a href="{$menu['products_classification']}" {if $url_path == "ProductsControllers" && ($url_path_action == "classification" || $url_path_action == "add_classification" || $url_path_action == "edit_classification")} class="current-page" {/if}>项目列表</a>
                                            </li>
                                            <li>
                                                <a href="{$menu['products_template']}" {if $url_path == "DocxControllers" && ($url_path_action == "template" || $url_path_action == "add_template" || $url_path_action == "edit_template")} class="current-page" {/if}>模板列表</a>
                                            </li>
                                        {/if}
                                        <li>
                                            <a href="{$menu['products_loophole_classification']}" {if $url_path == "ProductsControllers" && ($url_path_action == "loophole_classification" || $url_path_action == "add_loophole_classification" || $url_path_action == "edit_loophole_classification")} class="current-page" {/if}>漏洞分类</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade {if $url_path == "UserControllers"}show active{/if}" id="tab-authentication" role="tabpanel" aria-labelledby="authentication-tab">
                            <div class="tab-pane-header">
                                用户管理
                            </div>
                            <div class="sidebarMenuScroll">
                                <div class="sidebar-menu">
                                    <ul>
                                        {if $user_info['id'] == "1"}
                                            <li>
                                                <a href="{$menu['user_member']}" {if $url_path == "UserControllers" && $url_path_action == "member" || $url_path_action == "add_member" || $url_path_action == "edit_member"} class="current-page" {/if}>用户管理</a>
                                            </li>
                                        {/if}
                                        <li>
                                            <a href="{$menu['user_index']}" {if $url_path == "UserControllers" && $url_path_action == "index"} class="current-page" {/if}>个人中心</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        {if $user_info['id'] == "1"}
                            <div class="tab-pane fade {if $url_path == "LogControllers" or $url_path == "SetupControllers"}show active{/if}" id="tab-log" role="tabpanel" aria-labelledby="log-tab">
                                <div class="tab-pane-header">
                                    网站管理
                                </div>
                                <div class="sidebarMenuScroll">
                                    <div class="sidebar-menu">
                                        <ul>
                                            <li>
                                                <a href="{$menu['setup_index']}" {if $url_path == "SetupControllers" && $url_path_action == "index"} class="current-page" {/if}>网站设置</a>
                                            </li>
                                            <li>
                                                <a href="{$menu['log_index']}" {if $url_path == "LogControllers" && $url_path_action == "index"} class="current-page" {/if}>网站日志</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        {/if}
                    </div>
                </div>
            </nav>
            <div class="main-container">
                <div class="page-header">
                    <div class="row gutters">
                        <div class="col-xl-8 col-lg-8 col-md-8 col-sm-6 col-9">
                            <div class="search-container">
                                <div class="toggle-sidebar" id="toggle-sidebar">
                                    <i class="icon-menu"></i>
                                </div>
                                <div class="ui fluid category search">
                                    <div class="ui icon input">
                                        <input class="prompt" type="text" placeholder="Search">
                                        <i class="search icon icon-search1"></i>
                                    </div>
                                    <div class="results"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-3">

                            <!-- Header actions start -->
                            <ul class="header-actions">
                                <li class="dropdown">
                                    <a href="#" id="userSettings" class="user-settings" data-toggle="dropdown" aria-haspopup="true">
                                        <span class="avatar">
                                            <img src="{{$user_info['img']}}" onerror="javascript:this.src='./public/index/img/user.svg';" alt="User Avatar">
                                            <span class=""></span>
                                        </span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end md" aria-labelledby="userSettings">
                                        <div class="header-profile-actions">
                                            <a href="{$menu['user_index']}"><i class="icon-settings1"></i>个人中心</a>
                                            <a href="{$menu['login_logout']}"><i class="icon-log-out1"></i>退出</a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <!-- Header actions end -->
                        </div>
                    </div>
                </div>
                <div class="content-wrapper-scroll">
