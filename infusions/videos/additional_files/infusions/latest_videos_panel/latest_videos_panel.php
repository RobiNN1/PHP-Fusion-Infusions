<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) PHP-Fusion Inc
| https://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: latest_videos_panel.php
| Author: RobiNN
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (!defined('IN_FUSION')) {
    die('Access Denied');
}

$videos = function_exists('infusion_exists') ? infusion_exists('videos') : db_exists(DB_PREFIX.'videos');
if ($videos) {
    $result = dbquery("SELECT v.*, vc.video_cat_id, vc.video_cat_name, u.user_id, u.user_name, u.user_status, u.user_avatar, u.user_level, u.user_joined
        FROM ".DB_VIDEOS." v
        INNER JOIN ".DB_VIDEO_CATS." vc on v.video_cat = vc.video_cat_id
        LEFT JOIN ".DB_USERS." u ON v.video_user=u.user_id
        ORDER BY v.video_datestamp LIMIT 6
    ");

    $locale = fusion_get_locale('', VID_LOCALE);

    if (dbrows($result)) {
        openside($locale['VID_latest']);
        echo '<div class="list-group">';

        while ($data = dbarray($result)) {
            if ($data['video_type'] == 'youtube') {
                if (!empty($data['video_image']) && file_exists(VIDEOS.'images/'.$data['video_image'])) {
                    $thumbnail = VIDEOS.'images/'.$data['video_image'];
                } else {
                    $thumbnail = 'https://img.youtube.com/vi/'.$data['video_url'].'/maxresdefault.jpg';
                }
            } else if (!empty($data['video_image']) && file_exists(VIDEOS.'images/'.$data['video_image'])) {
                $thumbnail = VIDEOS.'images/'.$data['video_image'];
            } else {
                $thumbnail = VIDEOS.'images/default_thumbnail.jpg';
            }

            echo '<div class="list-group-item">';
                echo '<div class="pull-left m-r-15">';
                    echo '<a href="'.VIDEOS.'videos.php?video_id='.$data['video_id'].'" style="max-height: 56px; max-width: 100px;" class="display-inline-block image-wrap thumb text-center overflow-hide m-2">';
                        echo '<img class="img-responsive" src="'.$thumbnail.'" alt="'.$data['video_title'].'"/>';
                     echo '</a>';
                echo '</div>';

                echo '<div class="overflow-hide">';
                    echo '<a href="'.VIDEOS.'videos.php?video_id='.$data['video_id'].'"><span class="strong text-dark">'.$data['video_title'].'</span></a><br/>';

                    echo '<div>';
                        echo '<span><i class="fa fa-fw fa-folder"></i> '.$locale['VID_009'].' <a class="badge" href="'.VIDEOS.'videos.php?cat_id='.$data['video_cat_id'].'">'.$data['video_cat_name'].'</a></span>';
                        echo '<br/><span><i class="fa fa-fw fa-user"></i> '.profile_link($data['user_id'], $data['user_name'], $data['user_status']).'</span>';
                        echo '<br/><span><i class="fa fa-fw fa-clock-o"></i> '.$data['video_length'].'</span>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        }

        echo '</div>';
        closeside();
    }
}