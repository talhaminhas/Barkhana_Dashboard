<style>
    .breadcrumb{
        border-bottom-left-radius: 10px; 
        border-bottom-right-radius: 10px; 
        border-bottom: 1px solid; 
        border-left: 1px solid;
        border-right: 1px solid;
        background-color: rgba(0, 0, 255, 0.3);
        padding-left: 10px;  
        position: fixed;  
        top: 53px;  
        z-index: 1000;
        border-color: rgba(0, 0, 255, 0.3);
        
    }
    </style>
<ul class="breadcrumb" style="">

    <li class="breadcrumb-item">
        <a href="<?php echo site_url('/admin'); ?>" style="background-color: #3498db; color: #fff; padding: 5px 10px; border-radius: 5px; box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.5);">
            <?php echo get_msg('dashboard_label'); ?>
        </a>
    </li>

    <?php if (!empty($urls)): ?>

        <li class="breadcrumb-item">
            

            <a href="<?php echo $module_site_url; ?>" style="background-color: #3498db; color: #fff; padding: 5px 10px; border-radius: 5px; box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.5);">
                <?php echo ucfirst(strtolower($module_name)); ?>
            </a>
        </li>

        <?php if (!is_array($urls)): ?>

            <li class="breadcrumb-item">
               

                <span style="background-color: #ccc; padding: 5px 10px; border-radius: 5px; ">
                    <?php echo $urls; ?>
                </span>

            </li>

        <?php else: ?>

            <?php foreach ($urls as $url): ?>

                <li class="">

                    <?php if (!empty($url['url'])): ?>

                        <?php $link = $be_url . '/' . strtolower($module_name) . '/' . $url['url']; ?>

                        <a href="<?php echo $link; ?>" style="background-color: #3498db; color: #fff; padding: 5px 10px; border-radius: 5px;">

                            <?php echo $url['label']; ?>

                        </a>

                    <?php else: ?>

                        <span style="background-color: #ccc; padding: 5px 10px; border-radius: 5px;">

                            <?php echo $url['label']; ?>

                        </span>

                    <?php endif; ?>
                </li>

            <?php endforeach; ?>

        <?php endif; ?>

    <?php else: ?>

        <li class="breadcrumb-item">
            

            <span style="background-color: #ccc; padding: 5px 10px; border-radius: 5px;">
                <?php echo ucfirst(strtolower($module_name)); ?>
            </span>
        </li>

    <?php endif; ?>

</ul>
