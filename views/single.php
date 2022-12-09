<aside <?php post_class() ?>>

    <?php if (has_post_thumbnail()) { ?>
        <figure class="staff__image">
            <?php the_post_thumbnail(apply_filters('custom-post-type-staff-image-size', 'thumbnail'), ['class' => 'staff__image__img']); ?>
        </figure>
    <?php } ?>

    <div class="staff__wrapper">

        <header class="staff__header">
            <h1 class="staff__title"><?php the_title() ?></h1>
            <?php the_staff_meta('role', '<div class="staff__role">', '</div>') ?>
        </header>
        
        <div class="staff__content">
            <?php if (has_staff_meta('phone')) { ?>
                <a href="<?php the_staff_meta('phone', 'tel:')?>">
                    <?php the_staff_meta('phone', '<div class="staff__phone">', '</div>') ?>
                </a>
            <?php } ?>
            <?php if (has_staff_meta('role')) { ?>
                <a href="<?php the_staff_meta('email', 'mailto:')?>">
                    <?php the_staff_meta('email', '<div class="staff__email">', '</div>') ?>
                </a>
            <?php } ?>
        </div>

    </div>
     
</aside>
