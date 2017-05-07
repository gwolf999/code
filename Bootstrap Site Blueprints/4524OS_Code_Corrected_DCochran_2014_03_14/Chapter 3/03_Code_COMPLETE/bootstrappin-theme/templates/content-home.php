<?php while (have_posts()) : the_post(); ?>

	<div id="homepage-feature" class="carousel slide">
    <ol class="carousel-indicators">
        <li data-target="#homepage-feature" data-slide-to="0" class="active"></li>
        <li data-target="#homepage-feature" data-slide-to="1"></li>
        <li data-target="#homepage-feature" data-slide-to="2"></li>
        <li data-target="#homepage-feature" data-slide-to="3"></li>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner">
        <div class="item active">
					<?php $item="item1"; echo get_post_meta($post->ID, $item, true); ?>
				</div>
        <div class="item">
					<?php $item="item2"; echo get_post_meta($post->ID, $item, true); ?>
				</div>
        <div class="item">
					<?php $item="item3"; echo get_post_meta($post->ID, $item, true); ?>
				</div>
        <div class="item">
					<?php $item="item4"; echo get_post_meta($post->ID, $item, true); ?>
				</div>
		</div><!-- /.carousel-inner -->

    <!-- Controls -->
    <a class="left carousel-control" href="#homepage-feature" data-slide="prev">
        <span class="icon fa fa-chevron-left"></span>
    </a>
    <a class="right carousel-control" href="#homepage-feature" data-slide="next">
        <span class="icon fa fa-chevron-right"></span>
    </a>
  </div><!-- /#homepage-feature.carousel -->
  <div class="page-contents container">
		<div class="row">
		  <div class="col-sm-4">
		    <?php $column="column1"; echo get_post_meta($post->ID, $column, true); ?>
		  </div>
		  <div class="col-sm-4">
		    <?php $column="column2"; echo get_post_meta($post->ID, $column, true); ?>
		  </div>
		  <div class="col-sm-4">
		    <?php $column="column3"; echo get_post_meta($post->ID, $column, true); ?>
		  </div>
		</div><!-- /.row -->
	</div><!-- /.container -->


<?php endwhile; ?>