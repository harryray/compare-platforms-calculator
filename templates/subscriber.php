<?php

//Fusion_Dynamic_JS::enqueue_script( 'fusion-toggles' );

if (isset($_SESSION['i_is_version_removed']) && $_SESSION['i_is_version_removed'] == 1) {
    $_SESSION['i_is_version_removed'] == 0;
    unset($_SESSION['i_is_version_removed']);
?>
    <div class="fusion-alert alert success alert-dismissable alert-success alert-shadow">
        <button type="button" class="close toggle-alert" data-dismiss="alert" aria-hidden="true">×</button>
        <span class="alert-icon"><i class="fa fa-lg fa-check-circle"></i></span>
        Version removed successfully.
    </div>
<?php } else if (isset($_SESSION['i_is_version_removed']) && $_SESSION['i_is_version_removed'] == 2) {
    $_SESSION['i_is_version_removed'] == 0;
    unset($_SESSION['i_is_version_removed']);
?>
    <div class="fusion-alert alert error alert-dismissable alert-danger alert-shadow">
        <button type="button" class="close toggle-alert" data-dismiss="alert" aria-hidden="true">×</button>
        <span class="alert-icon"><i class="fa fa-lg fa-exclamation-triangle"></i></span>
        Error occurred while removing a version. Try again!
    </div>
<?php } ?>

<?php if (isset($_GET['message']) && (sanitize_text_field($_GET['message']) === 'email_success' || sanitize_text_field($_GET['message']) === 'version_saved')) : ?>
    <div class="fusion-alert alert success alert-dismissable alert-success alert-shadow">
        <button type="button" class="close toggle-alert" data-dismiss="alert" aria-hidden="true">×</button>
        <span class="alert-icon"><i class="fa fa-lg fa-check-circle"></i></span>
        <?php if (sanitize_text_field($_GET['message']) === 'version_saved') : ?>
            Your results have been saved!
        <?php endif; ?>
        <?php if (sanitize_text_field($_GET['message']) === 'email_success') : ?>
            Your results have been sent to <?php $user = wp_get_current_user();
                                            echo $user->user_email; ?>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php if (isset($_GET['message']) && sanitize_text_field($_GET['message']) === 'email_failed') : ?>
    <div class="fusion-alert alert error alert-dismissable alert-danger alert-shadow"> <button type="button" class="close toggle-alert" data-dismiss="alert" aria-hidden="true">×</button><span class="alert-icon"><i class="fa fa-lg fa-exclamation-triangle"></i></span>
        <?php if (sanitize_text_field($_GET['message']) === 'email_failed') : ?>
            Sending result to email <?php $user = wp_get_current_user();
                                    echo $user->user_email; ?> failed!
        <?php endif; ?>
    </div>
<?php endif; ?>
<?php
$user = wp_get_current_user();
$roles_arr = $user->roles;
global $wp_query;
$page_slug = $wp_query->queried_object->post_name;
if ($page_slug == 'client-area' && !isset($_GET['platform_id']) && !isset($_GET['version_id'])) {
?>
    <div class="subscriber-account">
        <fieldset class="account-summary">
            <legend><h1><?php _e('My account') ?></h1></legend>
            <div class="row">
                <div class="col-lg-4">
                    <span class="summary-label"><?php _e('First Name', 'cpalt'); ?></span>
                </div>
                <div class="col-lg-6">
                    <span class="summary-value"><?php echo esc_attr($subscriber->first_name); ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <span class="summary-label"><?php _e('Last Name', 'cpalt'); ?></span>
                </div>
                <div class="col-lg-6">
                    <span class="summary-value"><?php echo esc_attr($subscriber->last_name); ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <span class="summary-label"><?php _e('Email Address', 'cpalt'); ?></span>
                </div>
                <div class="col-lg-6">
                    <span class="summary-value"><?php echo esc_attr($subscriber->user_email); ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <span class="summary-label"><?php _e('Registered Since', 'cpalt'); ?></span>
                </div>
                <div class="col-lg-6">
                    <span class="summary-value"><?php echo esc_attr(date('d / m / Y', strtotime($subscriber->user_registered))); ?></span>
                </div>
            </div>
        </fieldset>
    </div>
    <?php /*}else{ echo '<div style="height:20px;"></div>'; } */ ?>
    <div class="row">
        <div class="col-lg-6 right-margin-fix">

            <div class="results-calculator-box">

                <h2><?php _e('Use our comparison tool', 'cplat'); ?></h2>
                <p><?php _e('This comparison tool enables investors to get real-time insight into the different DIY platforms and enables consumers to compare then in one easy-to-use, price-comparison tool.', 'cplat'); ?></p>
                <a href="<?php echo esc_url(get_permalink(get_page_by_title('Investment platform comparison tool'))) ?>" class="got-to-calculator-btn"><?php _e('Go To Comparison Tool', 'cplat'); ?></a>
                <h2><?php _e('View saved results', 'cplat'); ?></h2>
                <div class="accordian fusion-accordian">
                    <div class="panel-group" id="result-accordion">
                        <div class="fusion-panel panel-default">
                            <div class="panel-heading">
                                <h4 class="htb-panel-title-toggle" data-fontsize="14" data-lineheight="20"><a class="collapsed" data-toggle="collapse" data-parent="#result-accordion" data-target="#plat-cal-data" href="#plat-cal-data">
                                        <div class="fusion-toggle-icon-wrapper"><i class="fa-fusion-box"></i></div>
                                        <div class="fusion-toggle-heading">Investment platform comparison tool</div>
                                    </a></h4>
                            </div>
                            <div id="plat-cal-data" class="panel-collapse collapse" style="">
                                <div class="panel-body toggle-content fusion-clearfix">
                                    <table class="saved-calculator-results" id="type-platform">
                                        <thead>
                                            <th><?php _e('Date Saved', 'cplat'); ?></th>
                                            <th><?php _e('Name', 'cplat') ?></th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $is_reset_btn_p = false;
                                            if (!empty($saved_results)) {
                                                foreach ($saved_results as $key => $record) {
                                                    if (in_array('adviser', (array) $user->roles) || $user_type == 'advisor') {
                                                        $calculator_url = esc_url(site_url('platform-calculator'));
                                                    } else {
                                                        $calculator_url = esc_url(site_url('platform-calculator-consumer'));
                                                    }
                                                    $calculator_url = add_query_arg('version', $key, $calculator_url);
                                                    $calculator_url = add_query_arg('step', 4, $calculator_url);
                                                    if (($record['calculator_type'] != 'simplified' || !isset($record['calculator_type'])) && (!isset($record['is_deleted']) || $record['is_deleted'] == 'false')) {
                                                        $is_reset_btn_p = true;
                                            ?>
                                                        <tr>
                                                            <td><?php echo esc_attr(date('d/m/Y', $key)); ?></td>
                                                            <td><?php echo isset($record['version_name']) ? $record['version_name'] : '' ?></td>
                                                            <td><a href="<?php echo esc_url($calculator_url); ?>"><?php _e('View', 'cplat'); ?></a></td>
                                                            <td><a href="<?php echo esc_url('/client-area/?removeversion=' . $key) ?>"><?php _e('Remove', 'cplat'); ?></a></td>
                                                        </tr>
                                            <?php }
                                                }
                                            } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4"><?php if ($is_reset_btn_p == true) {
                                                                    echo '<button type="button">Delete all previous calculations</button>';
                                                                } ?></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="fusion-panel panel-default">
                            <div class="panel-heading">
                                <h4 class="htb-panel-title-toggle" data-fontsize="14" data-lineheight="20"><a class="collapsed" data-toggle="collapse" data-parent="#result-accordion" data-target="#simplified-cal-data" href="#simplified-cal-data">
                                        <div class="fusion-toggle-icon-wrapper"><i class="fa-fusion-box"></i></div>
                                        <div class="fusion-toggle-heading">Simple investment platform comparison tool</div>
                                    </a></h4>
                            </div>
                            <div id="simplified-cal-data" class="panel-collapse collapse" style="">
                                <div class="panel-body toggle-content fusion-clearfix">
                                    <table class="saved-calculator-results" id="type-simplified">
                                        <thead>
                                            <th><?php _e('Date Saved', 'cplat'); ?></th>
                                            <th><?php _e('Name', 'cplat') ?></th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $is_reset_btn_simp = false;
                                            if (!empty($saved_results)) {
                                                foreach ($saved_results as $key => $record) {
                                                    $calculator_url = esc_url(site_url('simplified-calculator'));
                                                    $calculator_url = add_query_arg('version', $key, $calculator_url);
                                                    $calculator_url = add_query_arg('step', 2, $calculator_url);
                                                    if ((isset($record['calculator_type']) && $record['calculator_type'] == 'simplified') && (!isset($record['is_deleted']) || $record['is_deleted'] == 'false')) {
                                                        $is_reset_btn_simp = true;
                                            ?>
                                                        <tr>
                                                            <td><?php echo esc_attr(date('d/m/Y', $key)); ?></td>
                                                            <td><?php echo isset($record['version_name']) ? $record['version_name'] : '' ?></td>
                                                            <td><a href="<?php echo esc_url($calculator_url); ?>"><?php _e('View', 'cplat'); ?></a></td>
                                                            <td><a href="<?php echo esc_url('/client-area/?removesimpleversion=' . $key) ?>"><?php _e('Remove', 'cplat'); ?></a></td>
                                                        </tr>
                                            <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4"><?php if ($is_reset_btn_simp == true) {
                                                                    echo '<button type="button">Delete all previous calculations</button>';
                                                                } ?></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="fusion-panel panel-default">
                            <div class="panel-heading">
                                <h4 class="htb-panel-title-toggle" data-fontsize="14" data-lineheight="20"><a data-toggle="collapse" data-parent="#result-accordion" data-target="#robo-data" href="#robo-data" class="collapsed">
                                        <div class="fusion-toggle-icon-wrapper"><i class="fa-fusion-box"></i></div>
                                        <div class="fusion-toggle-heading">Digital investing app comparison tool</div>
                                    </a></h4>
                            </div>
                            <div id="robo-data" class="panel-collapse collapse ">
                                <div class="panel-body toggle-content fusion-clearfix">
                                    <table class="saved-calculator-results" id="type-robo">
                                        <thead>
                                            <th><?php _e('Date Saved', 'cplat'); ?></th>
                                            <th><?php _e('Name', 'cplat') ?></th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $is_reset_btn_robo = false;
                                            if (!empty($saved_robo_results)) {
                                                foreach ($saved_robo_results as $key => $record) {
                                                    $calculator_url = esc_url(get_permalink(get_page_by_title('Robo calculator')));
                                                    $calculator_url = add_query_arg('robo_version', $key, $calculator_url);
                                                    if (date('Y', $key) >= 2000 && (!isset($record['is_deleted']) || $record['is_deleted'] == 'false')) {
                                                        $is_reset_btn_robo = true;
                                            ?>
                                                        <tr>
                                                            <td><?php echo esc_attr(date('d/m/Y', $key)); ?></td>
                                                            <td><?php echo isset($record['version_name']) ? $record['version_name'] : '' ?></td>
                                                            <td><a href="<?php echo esc_url($calculator_url); ?>"><?php _e('View', 'cplat'); ?></a></td>
                                                            <td><a href="<?php echo esc_url('/client-area/?removeroboversion=' . $key) ?>"><?php _e('Remove', 'cplat'); ?></a></td>
                                                        </tr>
                                            <?php
                                                    }
                                                }
                                            } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4"><?php if ($is_reset_btn_robo == 'true') {
                                                                    echo '<button type="button">Delete all previous calculations</button>';
                                                                } ?></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- Saved comparison results start -->
                        <div class="fusion-panel panel-default">
                            <div class="panel-heading">
                                <h4 class="htb-panel-title-toggle" data-fontsize="14" data-lineheight="20"><a data-toggle="collapse" data-parent="#result-accordion" data-target="#saved-comparisons" href="#saved-comparisons" class="collapsed">
                                        <div class="fusion-toggle-icon-wrapper"><i class="fa-fusion-box"></i></div>
                                        <div class="fusion-toggle-heading">Saved comparisons</div>
                                    </a></h4>
                            </div>
                            <div id="saved-comparisons" class="panel-collapse collapse ">
                                <div class="panel-body toggle-content fusion-clearfix">
                                    <table class="saved-calculator-results" id="type-saved-comparisons">
                                        <thead>
                                            <th><?php _e('Date Saved', 'cplat'); ?></th>
                                            <th><?php _e('Name', 'cplat') ?></th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $user_id = get_current_user_id();
                                            $saved_comparison_umeta = get_user_meta($user_id, '_ctp_saved_comparison_umeta', true);
                                            $is_reset_btn_robo = false;
                                            if (!empty($saved_comparison_umeta)) {
                                                foreach ($saved_comparison_umeta as $comp_key => $comp_record) {
                                                    if ($comp_record['calculator_type'] == 'robo') {
                                                        $calculator_url = esc_url(site_url('robo-comparisons'));
                                                        $calculator_url = add_query_arg('robo_version', $comp_key, $calculator_url);
                                                    } else {
                                                        $calculator_url = esc_url(site_url('platform-comparisons'));
                                                        $calculator_url = add_query_arg('platform_version', $comp_key, $calculator_url);
                                                    }
                                                    if (!isset($comp_record['is_deleted']) || $comp_record['is_deleted'] == false) {
                                                        $is_reset_btn_robo = true;
                                            ?>
                                                        <tr>
                                                            <td><?php echo date('d/m/Y', strtotime($comp_record['date_created'])) ?></td>
                                                            <td><?php echo isset($comp_record['version_name']) ? $comp_record['version_name'] : '' ?></td>
                                                            <td><a href="<?php echo esc_url($calculator_url); ?>"><?php _e('View', 'cplat'); ?></a></td>
                                                            <td><a href="javascript:" class="remove_comparison" id="<?php echo $comp_key; ?>"><?php _e('Remove', 'cplat'); ?></a></td>
                                                        </tr>
                                            <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4"><?php if ($is_reset_btn_robo == 'true') {
                                                                    echo '<button type="button">Delete all previous calculations</button>';
                                                                } ?></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- Saved comparison results end -->
                        <style>
                          .htb-panel-title-toggle a {
                            font-size: 14px;
                          }
                        </style>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 left-margin-fix">
            <div class="change-password-box">
                <h2><?php _e('Change password', 'cplat'); ?></h2>
                <form method="post" action="">
                    <label for="current-password"><?php _e('Current Password', 'cplat'); ?></label>
                    <input id="current-password" type="password" name="current_pass" value="" placeholder="<?php _e('My password is..', 'cplat'); ?>">
                    <label for="new-password"><?php _e('New Password', 'cplat'); ?></label>
                    <input id="new-password" type="password" name="new_pass" value="" placeholder="<?php _e('My new password is..', 'cplat'); ?>">
                    <label for="confirm_new-password"><?php _e('Confirm New Password', 'cplat'); ?></label>
                    <input id="confirm-new-password" type="password" name="confirm_new_pass" value="" placeholder="<?php _e('Confirm new password..', 'cplat'); ?>">
                    <div class="change-pass-submit-wrap">
                        <input type="hidden" name="action" value="change_pass">
                        <input class="btn btn-bright-green mt-4" type="submit" value="<?php _e('Change Password', 'cplat'); ?>" name="">
                    </div>
                </form>
            </div>
            <div class="delete-account-box">
                <h2><?php _e('Delete account', 'cplat'); ?></h2>
                <p>We’re sorry to see you go, but it’s very easy to remove your profile from our site. If you have any questions, please contact us at <a href="mailto:info@compareandinvest.co.uk">info@compareandinvest.co.uk</a></p>
                <?php
                $delete_url = add_query_arg('deleteaccount', 'true', home_url());
                ?>
                <a id="cplat-delete-account" href="<?php echo wp_nonce_url($delete_url, 'deleteuseronce' . $subscriber->ID); ?>" class="clear-btn"><?php _e('Delete Account Forever', 'cplat'); ?></a>
            </div>
        </div>
    </div>
<?php } ?>