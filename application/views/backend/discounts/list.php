<div class="table-responsive animated fadeInRight">
    <table id="discount-table" class="table m-0">
        <thead>
            <tr>
                <th class="table-header"><?php echo get_msg('no'); ?></th>
                <th class="table-header text-left"><?php echo get_msg('dis_name'); ?></th>

                <?php if ($this->ps_auth->has_access(EDIT)): ?>
                    <th class="table-header"><span class="th-title"><?php echo get_msg('btn_edit') ?></span></th>
                <?php endif; ?>

                <?php if ($this->ps_auth->has_access(DEL)): ?>
                    <th class="table-header"><span class="th-title"><?php echo get_msg('btn_delete') ?></span></th>
                <?php endif; ?>

                <?php if ($this->ps_auth->has_access(PUBLISH)): ?>
                    <th class="table-header"><span class="th-title"><?php echo get_msg('btn_publish') ?></span></th>
                <?php endif; ?>
            </tr>
        </thead>

        <tbody>
            <?php $count = $this->uri->segment(4) or $count = 0; ?>
            <?php if (!empty($discounts) && count($discounts->result()) > 0): ?>
                <?php foreach ($discounts->result() as $discount): ?>
                    <tr>
                        <td class="table-cell align-middle"><?php echo ++$count; ?></td>
                        <td class="align-middle"><?php echo $discount->name; ?></td>

                        <?php if ($this->ps_auth->has_access(EDIT)): ?>
                            <td class="table-cell align-middle">
                                <a href='<?php echo $module_site_url . '/edit/' . $discount->id; ?>'>
                                    <span class="btn btn-warning fixed-size-btn">Edit</span>
                                </a>
                            </td>
                        <?php endif; ?>

                        <?php if ($this->ps_auth->has_access(DEL)): ?>
                            <td class="table-cell align-middle">
                                <a href='#' class='btn-delete' data-toggle="modal" data-target="#discountmodal" id="<?php echo $discount->id; ?>">
                                    <span class="btn btn-danger fixed-size-btn">Delete</span>
                                </a>
                            </td>
                        <?php endif; ?>

                        <?php if ($this->ps_auth->has_access(PUBLISH)): ?>
                            <td class="table-cell align-middle">
                                <?php if (@$discount->status == 1): ?>
                                    <button class="btn fixed-size-btn btn-success unpublish" id='<?php echo $discount->id; ?>'>
                                        <?php echo get_msg('btn_yes'); ?>
                                    </button>
                                <?php else: ?>
                                    <button class="btn fixed-size-btn btn-danger publish" id='<?php echo $discount->id; ?>'>
                                        <?php echo get_msg('btn_no'); ?>
                                    </button>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center"><?php $this->load->view($template_path . '/partials/no_data'); ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
