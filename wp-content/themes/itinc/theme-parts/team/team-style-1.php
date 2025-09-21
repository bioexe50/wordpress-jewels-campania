<div class="themesion-post-item">
	<div class="themesion-team-image-box">
		<?php thsn_get_featured_data( array( 'size' => 'thsn-img-600x700' ) ); ?>
		<div class="themesion-box-social-links"><i class="thsn-base-icon-share"></i><?php echo thsn_team_social_links(); ?></div>
	</div>
	<div class="themesion-box-content">
		<div class="themesion-box-content-inner">
		<div class="themesion-box-team-position">
				<?php thsn_team_designation(); ?>
			</div>	
			<h3 class="thsn-team-title"><a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></h3>	
		</div>

	</div>
</div>
