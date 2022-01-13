<section class="staff">
    <div class="posts posts--staff">
        <?php
        while ($query->have_posts()) {
            $query->the_post();
            require apply_filters('custom-post-type-staff-single-template', plugin_dir_path(__FILE__).'single.php', $query, $attributes);
        }
        ?>
    </div>
</section>