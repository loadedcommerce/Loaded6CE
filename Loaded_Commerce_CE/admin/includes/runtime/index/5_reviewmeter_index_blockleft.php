<?php
/*
  $Id: 4_reviews_index_blockleft.php,v 1.0.0.0 2007/07/25 01:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
   if (defined(MAILBEEZ_MAILHIVE_STATUS) && MAILBEEZ_MAILHIVE_STATUS == 'True') { 


    $module_directory_current = DIR_FS_CATALOG . 'mailhive/dashboardbeez/dashboard_review_o_meter';
    $file_extension = '.php';

    if (file_exists($module_directory_current . '/languages/' . $_SESSION['language'] . $file_extension)) {
        // try to load language file
        include_once($module_directory_current . '/languages/' . $_SESSION['language'] . $file_extension);
    } elseif (file_exists($module_directory_current . '/languages/english' . $file_extension)) {
        // .. or english file as default if available
        include_once($module_directory_current . '/languages/english' . $file_extension);
    } else {
        // no language file found!
    }

    function review_o_meter_dbdate($day)
    {
        $rawtime = strtotime(-1 * (int)$day . " days");
        $ndate = date("Ymd", $rawtime);
        return $ndate;
    }

    $review_meter_factor = (defined('MAILBEEZ_DASHBOARD_REVIEW_O_METER_FACTOR'))
            ? MAILBEEZ_DASHBOARD_REVIEW_O_METER_FACTOR : 1;

    define('MAILBEEZ_DASHBOARD_REVIEW_O_METER_RATE_LIMIT_1', 6 * $review_meter_factor);
    define('MAILBEEZ_DASHBOARD_REVIEW_O_METER_RATE_LIMIT_2', 14 * $review_meter_factor);
    define('MAILBEEZ_DASHBOARD_REVIEW_O_METER_RATE_LIMIT_3', 20 * $review_meter_factor);


    $review_last_days = 30;

    $review_delay_days = 30; // delay between order and review
    $review_delay_days = (defined('MAILBEEZ_REVIEW_PASSED_DAYS')) ? MAILBEEZ_REVIEW_PASSED_DAYS : $review_delay_days;
    $review_delay_days = (defined('MAILBEEZ_REVIEW_ADVANCED_STEP1_DELAY')) ? MAILBEEZ_REVIEW_ADVANCED_STEP1_DELAY
            : $review_delay_days;
    $review_rate_max = MAILBEEZ_DASHBOARD_REVIEW_O_METER_RATE_LIMIT_3;


    $date_skip = review_o_meter_dbdate($review_last_days);
    $date_passed = review_o_meter_dbdate(-1);


    $order_status_id_str = ADMIN_BLOCKS_OT_APPROVED_ORDER_STATUS_MAP;

    $order_status_id_str = (defined('MAILBEEZ_DASHBOARD_REVIEW_O_METER_ORDER_STATUS_ID')) ? MAILBEEZ_DASHBOARD_REVIEW_O_METER_ORDER_STATUS_ID : $order_status_id_str;
    $order_status_id_str = (defined('MAILBEEZ_WINBACK_ORDER_STATUS_ID')) ? MAILBEEZ_WINBACK_ORDER_STATUS_ID : $order_status_id_str;
    $order_status_id_str = (defined('MAILBEEZ_WINBACK_ADVANCED_ORDER_STATUS_ID')) ? MAILBEEZ_WINBACK_ADVANCED_ORDER_STATUS_ID : $order_status_id_str;

    $customers_cnt_query_raw = "select count(distinct customers_id) as customers_cnt
                  from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_STATUS_HISTORY . " s
                  where o.orders_id = s.orders_id
                    and o.orders_status = s.orders_status_id
                    and s.orders_status_id in (" . $order_status_id_str . ")
                    and s.date_added <= '" . $date_passed . "'
                    and s.date_added > '" . $date_skip . "'";

    $customers_cnt_query = tep_db_query($customers_cnt_query_raw);
    $customers_cnt = tep_db_fetch_array($customers_cnt_query);

    // get the number of customers given a review
    $review_cnt_query_sql = "select count(distinct customers_id)  as review_cnt
                                from " . TABLE_REVIEWS . "
                             where date_added <= '" . $date_passed . "'
                                and date_added > '" . $date_skip . "' ";
    $review_cnt_query = tep_db_query($review_cnt_query_sql);
    $review_cnt = tep_db_fetch_array($review_cnt_query);


    $reviews_count = $review_cnt['review_cnt'];
    $customers_count = $customers_cnt['customers_cnt'];


    if ($customers_count > 0) {
        $review_rate = 100 * $reviews_count / $customers_count;
        ;
    } else {
        $review_rate = 0;
    }

    $review_show_fix_hint = true;

    if ($review_rate == 0) {
        $review_rate_title = MAILBEEZ_DASHBOARD_REVIEW_O_METER_RATE_MSG_1;
        $rate_color = '#f8933c';
        $rate_color_text = '#b85c0c';
    }
    if ($review_rate > 0) {
        $review_rate_title = MAILBEEZ_DASHBOARD_REVIEW_O_METER_RATE_MSG_2;
        $rate_color = '#f8933c';
        $rate_color_text = '#b85c0c';
    }
    if ($review_rate > MAILBEEZ_DASHBOARD_REVIEW_O_METER_RATE_LIMIT_1) {
        $review_rate_title = MAILBEEZ_DASHBOARD_REVIEW_O_METER_RATE_MSG_3;
        $rate_color = '#fff7a5';
        $rate_color_text = '#aba032';
    }
    if ($review_rate > MAILBEEZ_DASHBOARD_REVIEW_O_METER_RATE_LIMIT_2) {
        $review_rate_title = MAILBEEZ_DASHBOARD_REVIEW_O_METER_RATE_MSG_4;
        $rate_color = '#abd37f';
        $rate_color_text = '#337c4e';
        $review_show_fix_hint = false;
    }
    if ($review_rate >= MAILBEEZ_DASHBOARD_REVIEW_O_METER_RATE_LIMIT_3) {
        $review_rate_title = MAILBEEZ_DASHBOARD_REVIEW_O_METER_RATE_MSG_5;
        $rate_color = '#abd37f';
        $rate_color_text = '#337c4e';
        $review_show_fix_hint = false;
    }

    $review_show_fix_hint = true;

    ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" summary="Reviews Information">
    <tr valign="top">
        <td width="100%" style="padding-right: 12px;">
            <div class="form-head-light"><?php cre_index_block_title(BLOCK_TITLE_REVIEWS, tep_href_link(FILENAME_REVIEWS, 'selected_box=catalog', 'NONSSL'), BLOCK_HELP_REVIEWS);?></div>
            <div class="form-body form-body-fade">
                <script language="javascript" src="../mailhive/common/js/jquery.min-1.5.1.js"></script>
                <!--[if IE]>
                <script type="text/javascript"
                        src="../mailhive/common/js/excanvas.min.js"></script>
                <![endif]-->
                <script type="text/javascript"
                        src="../mailhive/common/js/gauge.js"></script>
                <script type="text/javascript"
                        src="../mailhive/common/js/jquery.gauge.js"></script>
                <table width="100%">
                    <tr>
                        <td>
                            <div style="float: left; z-index: 5; position: relative;">
                                <canvas id="review" width="100" height="100" style="margin-left: 10px;"></canvas>
                            </div>
                            <script type="text/javascript">
                                jQuery.noConflict();
                                jQuery(document).ready(function() {
                                    jQuery("#review")
                                            .gauge({
                                                colorOfText: '#303030',
                                                colorOfWarningText: '#597c32',
                                                colorOfFill: [ '#888', '#333', '#ddd', '#fcfcfc' ],
                                                colorOfPointerFill: '#909090',
                                                colorOfPointerStroke: '#909090',
                                                colorOfCenterCircleFill : '#909090',
                                                colorOfCenterCircleStroke: '#909090',
                                                majorTicks: 1,
                                                minorTicks: 1,
                                                min: 0,
                                                max: <?php echo $review_rate_max; ?>,
                                                label: 'RATE',
                                                unitsLabel: '%',
                                                bands: [
                                                    {color: "#f8933c", from: 0, to: <?php echo MAILBEEZ_DASHBOARD_REVIEW_O_METER_RATE_LIMIT_1; ?>},
                                                    {color: "#fff7a5", from: <?php echo MAILBEEZ_DASHBOARD_REVIEW_O_METER_RATE_LIMIT_1; ?>, to: <?php echo MAILBEEZ_DASHBOARD_REVIEW_O_METER_RATE_LIMIT_2; ?>},
                                                    {color: "#abd37f", from: <?php echo MAILBEEZ_DASHBOARD_REVIEW_O_METER_RATE_LIMIT_2; ?>, to: <?php echo MAILBEEZ_DASHBOARD_REVIEW_O_METER_RATE_LIMIT_3; ?>}
                                                ]
                                            })
                                });
                                setTimeout('jQuery("#review").gauge("setValue", <?php echo $review_rate; ?>)', 300);

                            </script>
                            <div style="margin-left: 0px; border: 0px solid red; position: relative; z-index: 2; min-height: 100px;">
                                <div style="margin-left: 120px; margin-top: 0px;">
                                    <?php echo sprintf(MAILBEEZ_DASHBOARD_REVIEW_O_METER_RATE_TEXT, $review_rate)?>
                                </div>
                                <div style=" margin-top: 7px; background-color: <?php echo $rate_color; ?>; padding: 5px; text-align: center; color: <?php echo $rate_color_text; ?>; font-weight: bold; font-size: 12px;"><?php echo $review_rate_title; ?></div>
                                <?php if ($review_show_fix_hint) { ?>
                                <div style="margin-left: 120px; margin-top: 12px;">
                                    <a href="<?php echo tep_href_link(FILENAME_MAILBEEZ, 'selected_box=marketing', 'NONSSL') ?>"
                                            ><?php echo MAILBEEZ_DASHBOARD_REVIEW_O_METER_FIX_TEXT_LINK_ADMIN_DASHBOARD;?></a>
                                </div>
                                <?php }?>
                            </div>
                            <div style="position: relative; bottom: 0px;">
                                <?php echo sprintf(MAILBEEZ_DASHBOARD_REVIEW_O_METER_STATS_TEXT, $review_last_days, $reviews_count, $customers_count, $review_delay_days); ?>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>
<?php

}
?>