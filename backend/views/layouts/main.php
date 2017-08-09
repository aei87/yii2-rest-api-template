<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\URL;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>



<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">

<?php $this->beginBody() ?>

    <header class="app-header navbar">
        <button class="navbar-toggler mobile-sidebar-toggler d-lg-none" type="button">☰</button>
        <a class="navbar-brand" href="#"></a>
        <ul class="nav navbar-nav d-md-down-none">
            <li class="nav-item">
                <a class="nav-link navbar-toggler sidebar-toggler" href="#">☰</a>
            </li>

            <li class="nav-item px-3">
                <a class="nav-link" href="#">Dashboard</a>
            </li>
            <li class="nav-item px-3">
                <a class="nav-link" href="#">Users</a>
            </li>
            <li class="nav-item px-3">
                <a class="nav-link" href="#">Settings</a>
            </li>
        </ul>
        <ul class="nav navbar-nav ml-auto">
            <li class="nav-item d-md-down-none">
                <a class="nav-link" href="#"><i class="icon-bell"></i><span class="badge badge-pill badge-danger">5</span></a>
            </li>
            <li class="nav-item d-md-down-none">
                <a class="nav-link" href="#"><i class="icon-list"></i></a>
            </li>
            <li class="nav-item d-md-down-none">
                <a class="nav-link" href="#"><i class="icon-location-pin"></i></a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <img src="img/avatars/6.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                    <span class="d-md-down-none">admin</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">

                    <div class="dropdown-header text-center">
                        <strong>Account</strong>
                    </div>

                    <a class="dropdown-item" href="#"><i class="fa fa-bell-o"></i> Updates<span class="badge badge-info">42</span></a>
                    <a class="dropdown-item" href="#"><i class="fa fa-envelope-o"></i> Messages<span class="badge badge-success">42</span></a>
                    <a class="dropdown-item" href="#"><i class="fa fa-tasks"></i> Tasks<span class="badge badge-danger">42</span></a>
                    <a class="dropdown-item" href="#"><i class="fa fa-comments"></i> Comments<span class="badge badge-warning">42</span></a>

                    <div class="dropdown-header text-center">
                        <strong>Settings</strong>
                    </div>

                    <a class="dropdown-item" href="#"><i class="fa fa-user"></i> Profile</a>
                    <a class="dropdown-item" href="#"><i class="fa fa-wrench"></i> Settings</a>
                    <a class="dropdown-item" href="#"><i class="fa fa-usd"></i> Payments<span class="badge badge-default">42</span></a>
                    <a class="dropdown-item" href="#"><i class="fa fa-file"></i> Projects<span class="badge badge-primary">42</span></a>
                    <div class="divider"></div>
                    <a class="dropdown-item" href="#"><i class="fa fa-shield"></i> Lock Account</a>
                    <a class="dropdown-item" href="#"><i class="fa fa-lock"></i> Logout</a>
                </div>
            </li>
            <li class="nav-item d-md-down-none">
                <a class="nav-link navbar-toggler aside-menu-toggler" href="#">☰</a>
            </li>

        </ul>
    </header>

    <div class="app-body">
        <div class="sidebar">
            <nav class="sidebar-nav">
                
                <?php

                    $controller = 'mediapress';

                    $menu_list =  array();

                    /* Main site */

                    //$menu_list['index'] = '/site/index';


                    /* MediaPress */

                    $menu_list['mediapress'] = array();

                    $menu_list['mediapress']['label'] = 'MediaPress';
                    $menu_list['mediapress']['icon'] = 'icon-screen-desktop';

                    $menu_list['mediapress']['items']['create_gallery']['action'] = 'create_gallery';
                    $menu_list['mediapress']['items']['create_gallery']['label'] = 'Create Gallery';

                    $menu_list['mediapress']['items']['delete_galery']['action'] = 'delete_gallery';
                    $menu_list['mediapress']['items']['delete_galery']['label'] = 'Delete Gallery';

                    $menu_list['mediapress']['items']['rename_gallery']['action'] = 'rename_gallery';
                    $menu_list['mediapress']['items']['rename_gallery']['label'] = 'Rename Gallery';

                    $menu_list['mediapress']['items']['add_media']['action'] = 'add_media';
                    $menu_list['mediapress']['items']['add_media']['label'] = 'Create Photo';

                    /*
                    $menu_list['mediapress']['items']['update_media']['action'] = 'update_media';
                    $menu_list['mediapress']['items']['update_media']['label'] = 'Update Media';
                    */

                    $menu_list['mediapress']['items']['delete_media']['action'] = 'delete_media';
                    $menu_list['mediapress']['items']['delete_media']['label'] = 'Delete Photo';


                    /* BuddyPress */

                    $menu_list['buddypress'] = array();

                    $menu_list['buddypress']['label'] = 'BuddyPress';
                    $menu_list['buddypress']['icon'] = 'icon-user-female';

                    $menu_list['buddypress']['items']['create_group']['action'] = 'create_group';
                    $menu_list['buddypress']['items']['create_group']['label'] = 'Create Group';

                    $menu_list['buddypress']['items']['invite_user']['action'] = 'invite_user';
                    $menu_list['buddypress']['items']['invite_user']['label'] = 'Invite User';

                    $menu_list['buddypress']['items']['invite_shetrades']['action'] = 'invite_shetrades';
                    $menu_list['buddypress']['items']['invite_shetrades']['label'] = 'Invite Shetrades';

                    $menu_list['buddypress']['items']['join_group']['action'] = 'join_group';
                    $menu_list['buddypress']['items']['join_group']['label'] = 'Join Group';

                    $menu_list['buddypress']['items']['get_topics']['action'] = 'get_topics';
                    $menu_list['buddypress']['items']['get_topics']['label'] = 'Get Topics';

                    $menu_list['buddypress']['items']['add_topic']['action'] = 'create_topic';
                    $menu_list['buddypress']['items']['add_topic']['label'] = 'Create Topic';

                    $menu_list['buddypress']['items']['get_replies']['action'] = 'get_replies';
                    $menu_list['buddypress']['items']['get_replies']['label'] = 'Get Replies';


                    /* Events */

                    $menu_list['events'] = array();

                    $menu_list['events']['label'] = 'Events';
                    $menu_list['events']['icon'] = 'icon-event';

                    $menu_list['events']['items']['get_events']['action'] = 'get_events';
                    $menu_list['events']['items']['get_events']['label'] = 'Get events';


                ?>



                <ul class="nav">

                    <li class="nav-title">
                        Rest API
                    </li>

                    <?php

                    foreach ($menu_list as $menu_id => $menu) {
                        
                        ?>

                            <li class="nav-item nav-dropdown">
                                <a class="nav-link nav-dropdown-toggle" href="#"><i class="<?php echo $menu['icon']; ?> "></i><?php echo $menu['label']; ?> </a>
                                <ul class="nav-dropdown-items">
                                    
                                    <?php

                                    foreach ($menu['items'] as $item_id => $item) {
                                        
                                        ?>
                                        
                                        <li class="nav-item">
                                            <a class="nav-link" href="<?php echo Url::to([$menu_id.'/'.$item['action']]); ?>" style="margin-left: 30px;"><?php echo $item['label']; ?> </a>
                                        </li>
                                        
                                        <?php

                                    }

                                    ?>
                                    
                                </ul>
                            </li>

                        <?php
                    }

                    ?>


                    <li class="nav-title">
                        Other
                    </li>

                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-puzzle"></i> Components <span class="badge badge-info"></span></a>
                        <ul class="nav-dropdown-items">
                            <li class="nav-item">
                                <a class="nav-link" href="components-buttons.html"><i class="icon-puzzle"></i> Buttons</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="components-social-buttons.html"><i class="icon-puzzle"></i> Social Buttons</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="components-cards.html"><i class="icon-puzzle"></i> Cards</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="components-forms.html"><i class="icon-puzzle"></i> Forms</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="components-modals.html"><i class="icon-puzzle"></i> Modals</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="components-switches.html"><i class="icon-puzzle"></i> Switches</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="components-tables.html"><i class="icon-puzzle"></i> Tables</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="components-tabs.html"><i class="icon-puzzle"></i> Tabs</a>
                            </li>
                        </ul>
                    </li>


                    

                </ul>
            </nav>
        </div>

        <!-- Main content -->
        <main class="main">

            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item"><a href="#">Admin</a>
                </li>
                <li class="breadcrumb-item active">Dashboard</li>

                <!-- Breadcrumb Menu-->
                <li class="breadcrumb-menu d-md-down-none">
                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                        <a class="btn btn-secondary" href="#"><i class="icon-speech"></i></a>
                        <a class="btn btn-secondary" href="./"><i class="icon-graph"></i> &nbsp;Dashboard</a>
                        <a class="btn btn-secondary" href="#"><i class="icon-settings"></i> &nbsp;Settings</a>
                    </div>
                </li>
            </ol>


            <div class="container-fluid">

                <?= $content ?>

            </div>
            <!-- /.conainer-fluid -->
        </main>



    </div>

    <footer class="app-footer">
        <a href="http://coreui.io">CoreUI</a> © 2017 creativeLabs.
        <span class="float-right">Powered by <a href="http://coreui.io">CoreUI</a>
        </span>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
