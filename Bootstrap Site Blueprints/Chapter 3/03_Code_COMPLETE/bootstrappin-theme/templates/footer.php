<footer class="content-info container" role="contentinfo">
  <div class="row">
    <div class="col-lg-12">
    	<p><a href="<?php echo home_url(); ?>/"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo.png" width="80" alt="Bootstrappin'"></a></p>
      <?php dynamic_sidebar('sidebar-footer'); ?>
      <!-- <p>&copy; <?php // echo date('Y'); ?> <?php // bloginfo('name'); ?></p> -->
    </div>
  </div>
</footer>

<?php wp_footer(); ?>