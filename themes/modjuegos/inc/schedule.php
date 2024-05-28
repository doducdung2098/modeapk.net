<?php
add_action('wp', 'updating_cron_job'); // Schedule Cron Job Event
function updating_cron_job(){
    if (!wp_next_scheduled('hit_update_version')) {
        wp_schedule_event(time(), 'minutely', 'k_version_checking'); //action in inc/version_checking.php
    }
}

add_filter('cron_schedules', 'cron_job_recurrence'); // Custom Cron Recurrences
function cron_job_recurrence($schedules){
    $schedules['minutely'] = array(
        'display' => 'Once Per Minute',
        'interval' => 60,
    );
    return $schedules;
}