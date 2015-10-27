<?php
/*
  $Id: 4_winbacks_index_blockleft.php,v 1.0.0.0 2007/07/25 01:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (defined(MAILBEEZ_MAILHIVE_STATUS) && MAILBEEZ_MAILHIVE_STATUS == 'True') {
    $module_directory_current = DIR_FS_CATALOG . 'mailhive/dashboardbeez/dashboard_winback_o_meter';
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

    function winback_o_meter_dbdate($day)
    {
        $rawtime = strtotime(-1 * (int)$day . " days");
        $ndate = date("Ymd", $rawtime);
        return $ndate;
    }


    $winback_meter_factor = (defined('MAILBEEZ_DASHBOARD_WINBACK_O_METER_FACTOR'))
            ? MAILBEEZ_DASHBOARD_WINBACK_O_METER_FACTOR : 1;

    define('MAILBEEZ_DASHBOARD_WINBACK_O_METER_RATE_LIMIT_1', 3 * $winback_meter_factor);
    define('MAILBEEZ_DASHBOARD_WINBACK_O_METER_RATE_LIMIT_2', 7 * $winback_meter_factor);
    define('MAILBEEZ_DASHBOARD_WINBACK_O_METER_RATE_LIMIT_3', 10 * $winback_meter_factor);


    $winback_delay_days = MAILBEEZ_DASHBOARD_WINBACK_O_METER_DELAY_DAYS;
    $winback_last_days = MAILBEEZ_DASHBOARD_WINBACK_O_METER_PASSED_DAYS_SKIP;

    $winback_rate_max = MAILBEEZ_DASHBOARD_WINBACK_O_METER_RATE_LIMIT_3;

    $date_email_passed = winback_o_meter_dbdate(-1);
    $date_email_skip = winback_o_meter_dbdate($winback_last_days + $winback_delay_days);

    // get a list of customers who got a winback reactivation email in the last e.g. 10 days
    $winback_email_query_sql = "select distinct customers_id
                                from " . TABLE_MAILBEEZ_TRACKING . "
                             where module like 'winback%'
                                and date_sent <= '" . $date_email_passed . "'
                                and date_sent > '" . $date_email_skip . "' ";
    $winback_email_query = tep_db_query($winback_email_query_sql);


    // all customers who got a winback email
    $winback_email_customers_id_array = array();
    while ($winback_email = tep_db_fetch_array($winback_email_query)) {
        $winback_email_customers_id_array[] = $winback_email['customers_id'];
    }
    $winback_email_customers_id_list = implode(',', $winback_email_customers_id_array);

    $winback_email_count = sizeof($winback_email_customers_id_array);


    // see how many of these customers placed an order in the timeframe
    $date_skip_orders = winback_o_meter_dbdate($winback_last_days);
    $date_passed_orders = winback_o_meter_dbdate(-1);

    $winback_orders_cnt_query_raw = "select count(distinct customers_id) as customers_cnt
                  from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_STATUS_HISTORY . " s
                  where o.customers_id in (" . $winback_email_customers_id_list . ")
                    and o.date_purchased <= '" . $date_passed_orders . "'
                    and o.date_purchased > '" . $date_skip_orders . "'";

    $winback_orders_count = 0;
    if ($winback_email_count > 0) {
        $winback_orders_cnt_query = tep_db_query($winback_orders_cnt_query_raw);
        $winback_orders_cnt = tep_db_fetch_array($winback_orders_cnt_query);
        $winback_orders_count = $winback_orders_cnt['customers_cnt'];
    }

    if ($winback_email_count > 0) {
        $winback_rate = 100 * $winback_orders_count / $winback_email_count;
    } else {
        $winback_rate = 0;
    }

    $winback_show_fix_hint = true;

    if ($winback_rate == 0) {
        $winback_rate_title = MAILBEEZ_DASHBOARD_WINBACK_O_METER_RATE_MSG_1;
        $rate_color = '#f8933c';
        $rate_color_text = '#b85c0c';
    }
    if ($winback_rate > 0) {
        $winback_rate_title = MAILBEEZ_DASHBOARD_WINBACK_O_METER_RATE_MSG_2;
        $rate_color = '#f8933c';
        $rate_color_text = '#b85c0c';
    }
    if ($winback_rate > MAILBEEZ_DASHBOARD_WINBACK_O_METER_RATE_LIMIT_1) {
        $winback_rate_title = MAILBEEZ_DASHBOARD_WINBACK_O_METER_RATE_MSG_3;
        $rate_color = '#fff7a5';
        $rate_color_text = '#aba032';
    }
    if ($winback_rate > MAILBEEZ_DASHBOARD_WINBACK_O_METER_RATE_LIMIT_2) {
        $winback_rate_title = MAILBEEZ_DASHBOARD_WINBACK_O_METER_RATE_MSG_4;
        $rate_color = '#abd37f';
        $rate_color_text = '#337c4e';
        $winback_show_fix_hint = false;
    }
    if ($winback_rate >= MAILBEEZ_DASHBOARD_WINBACK_O_METER_RATE_LIMIT_3) {
        $winback_rate_title = MAILBEEZ_DASHBOARD_WINBACK_O_METER_RATE_MSG_5;
        $rate_color = '#abd37f';
        $rate_color_text = '#337c4e';
        $winback_show_fix_hint = false;
    }

     $winback_show_fix_hint = true;

    ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" summary="Reviews Information">
    <tr valign="top">
        <td width="100%" style="padding-right: 12px;">
            <div class="form-head-light"><?php cre_index_block_title(BLOCK_TITLE_CUSTOMERS, tep_href_link(FILENAME_CREATE_ACCOUNT, 'selected_box=customers', 'NONSSL'), BLOCK_HELP_CUSTOMERS);?></div>
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
                                <canvas id="winback" width="100" height="100" style="margin-left: 10px;"></canvas>
                            </div>
                            <script type="text/javascript">
                                jQuery.noConflict();
                                jQuery(document).ready(function() {
                                    jQuery("#winback")
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
                                                max: <?php echo $winback_rate_max; ?>,
                                                label: 'RATE',
                                                unitsLabel: '%',
                                                bands: [
                                                    {color: "#f8933c", from: 0, to: <?php echo MAILBEEZ_DASHBOARD_WINBACK_O_METER_RATE_LIMIT_1; ?>},
                                                    {color: "#fff7a5", from: <?php echo MAILBEEZ_DASHBOARD_WINBACK_O_METER_RATE_LIMIT_1; ?>, to: <?php echo MAILBEEZ_DASHBOARD_WINBACK_O_METER_RATE_LIMIT_2; ?>},
                                                    {color: "#abd37f", from: <?php echo MAILBEEZ_DASHBOARD_WINBACK_O_METER_RATE_LIMIT_2; ?>, to: <?php echo MAILBEEZ_DASHBOARD_WINBACK_O_METER_RATE_LIMIT_3; ?>}
                                                ]
                                            })
                                });
                                setTimeout('jQuery("#winback").gauge("setValue", <?php echo $winback_rate; ?>)', 300);

                            </script>
                            <div style="margin-left: 0px; border: 0px solid red; position: relative; z-index: 2; min-height: 100px;">
                                <div style="margin-left: 120px; margin-top: 0px;">
                                    <?php echo sprintf(MAILBEEZ_DASHBOARD_WINBACK_O_METER_RATE_TEXT, $winback_rate)?>
                                </div>
                                <div style=" margin-top: 7px; background-color: <?php echo $rate_color; ?>; padding: 5px; text-align: center; color: <?php echo $rate_color_text; ?>; font-weight: bold; font-size: 12px;"><?php echo $winback_rate_title; ?></div>
                                <?php if ($winback_show_fix_hint) { ?>
                                <div style="margin-left: 120px; margin-top: 12px;">
                                    <a href="<?php echo tep_href_link(FILENAME_MAILBEEZ, 'selected_box=marketing', 'NONSSL') ?>"
                                       ><?php echo MAILBEEZ_DASHBOARD_WINBACK_O_METER_FIX_TEXT_LINK_ADMIN_DASHBOARD;?></a>
                                </div>
                                <?php }?>
                            </div>
                            <div style="position: relative; bottom: 3px;">
                                <?php echo sprintf(MAILBEEZ_DASHBOARD_WINBACK_O_METER_STATS_TEXT, $winback_last_days, $winback_orders_count, $winback_email_count, $winback_delay_days); ?>
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