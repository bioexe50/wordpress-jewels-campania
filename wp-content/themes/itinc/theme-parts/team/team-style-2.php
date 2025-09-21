<div class="themesion-post-item">
	<div class="themesion-team-image-box">		
		<div class="themesion-box-social-links"><?php echo thsn_team_social_links(); ?></div>	
		<?php thsn_get_featured_data( array( 'size' => 'thsn-img-500x700' ) ); ?>
	</div>
	<div class="themesion-box-content">		
		<h3 class="thsn-team-title"><a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></h3>
		<div class="themesion-box-team-position">
			<?php thsn_team_designation(); ?>
		</div>		
	</div>		
</div>

 