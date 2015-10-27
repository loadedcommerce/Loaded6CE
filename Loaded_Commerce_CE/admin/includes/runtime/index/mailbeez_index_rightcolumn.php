<?php
/*
  $Id: mailbeez_index_rightcolumn.php,v 1.0.0.0 2011/07/11 cord Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (!defined(MAILBEEZ_MAILHIVE_STATUS) && MAILBEEZ_MAILHIVE_STATUS != 'True') {
    ?>
<script type="text/javascript">
    <!--
    function toggle_visibility(mailbeez) {
        var mb = document.getElementById(mailbeez);
        if (mb.style.display == 'block')
            mb.style.display = 'none';
        else
            mb.style.display = 'block';
    }
    //-->
</script>
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top: 1em;">
    <tr>
        <td class="box-top-left">&nbsp;</td>
        <td class="box-top">&nbsp;</td>
        <td class="box-top-right">&nbsp;</td>
    </tr>
    <tr>
        <td class="box-left">&nbsp;</td>
        <td class="box-content">
            <a href="http://www.mailbeez.com/?a=cre" target="_blank">

                <div style="border:1px solid #05759e; margin-bottom: 5px;">
                    <div style="background-color: #05759e; height: 45px;"><img src="images/mailbeez.png"
                                                                               style='width:130px; display:block; margin:0 auto 10px auto; border:0;'/>
                    </div>
                </div>


            </a>

            <div style='width:100%; text-align:center; font-weight:bold'>easy automated email marketing</div>
            <p align="center"><a href="#" onclick="toggle_visibility('mailbeez');">more info...</a></p>

            <div id="mailbeez" style="display:none;">
                With MailBeez you can establish a professional automized customer communication, e.g. for
                <ul style='padding:0px;margin:5px 0px 0px 6px;list-style-type:disc; font-size:10px'>
                    <li>Review Reminder</li>
                    <li>Winback Emails</li>
                    <li>Customer satisfaction surveys</li>
                    <li>Birthday and Season Greetings</li>
                    <li>Service Emails for e.g. wearing parts, maintenance updates</li>
                    <li>Payment reminder of e.g. Cash in Advanced or Invoice payments</li>
                </ul>
                <div style='margin-top:6px;'>
                    Try everything with free modules and upgrade to premium campaigns!
                </div>
                <br/>
            </div>
            <div style='width:100%; text-align:center; font-weight:bold'>
                <a href='mailbeez.php'>Enable This Module</a>
            </div>
        </td>
        <td class="box-right">&nbsp;</td>
    </tr>
    <tr>
        <td class="box-bottom-left">&nbsp;</td>
        <td class="box-bottom">&nbsp;</td>
        <td class="box-bottom-right">&nbsp;</td>
    </tr>
</table>
<?php

}
?>