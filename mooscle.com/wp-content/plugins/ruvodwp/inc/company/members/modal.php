<div class="modal new-member-modal fade" tabindex="-1" role="dialog" id="member-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content loader traditional">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">
                    <?php echo __('Add user to company member',RUVOD_TEXT_DOMAIN) ?>
                </h4>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#new"><?php echo __('New user',RUVOD_TEXT_DOMAIN) ?></a></li>
                    <li><a data-toggle="tab" href="#existed"><?php echo __('Existed user',RUVOD_TEXT_DOMAIN) ?></a></li>
                </ul>

                <div class="tab-content">
                    <div id="new" class="tab-pane fade in active">
                        <form class="ajax-form"  action="/wp-admin/admin-ajax.php" method="post">
                            <input type="hidden" name="action" value="create_user_member">
                            <label for="title"><?php _e('Enter email', RUVOD_TEXT_DOMAIN) ?></label>
                            <input type="email" required class="form-control"  name="email" placeholder="<?php _e('EMAIL', RUVOD_TEXT_DOMAIN) ?>">
                            <p>
                                <?php _e('On the email will come instructions for the entrance', RUVOD_TEXT_DOMAIN) ?>
                            </p>
                            <input type="submit" class="submit hidden">
                            <div style="display:none;" class="alert alert-danger" role="alert"></div>
                            <div style="display:none;" class="alert alert-success" role="alert"></div>
                        </form>
                    </div>
                    <div id="existed" class="tab-pane fade">
                        <form class="ajax-form"  action="/wp-admin/admin-ajax.php" method="post">
                            <input type="hidden" name="action" value="add_user_member">
                            <label for="title"><?php _e('Enter email', RUVOD_TEXT_DOMAIN) ?></label>
                            <input type="email" required class="form-control"  name="email" placeholder="<?php _e('EMAIL', RUVOD_TEXT_DOMAIN) ?>">
                            <p>
                                <?php _e("Specify the user's email address, and we'll add it to the company", RUVOD_TEXT_DOMAIN) ?>
                            </p>
                            <input type="submit" class="submit hidden">
                            <div style="display:none;" class="alert alert-danger" role="alert"></div>
                            <div style="display:none;" class="alert alert-success" role="alert"></div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary pull-left submit">
                    <?php echo __('Next',RUVOD_TEXT_DOMAIN) ?>
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>