<div class="thsn-fld-contents">
	<div class="thsn-circle-outer"
		data-id 	 = "100"
		data-digit			= "<?php echo esc_html($digit); ?>"
		data-fill			= "<?php echo esc_html($global_color); ?>"		
		data-emptyfill		= "<?php echo thsn_hex2rgb($global_color, '0.10') ?>"
		data-before			= "<?php echo esc_html($before_text); ?>"
		data-before-type	= "<?php echo esc_html($beforetextstyle); ?>"
		data-after			= "<?php echo esc_html($after_text); ?>"
		data-after-type		= "<?php echo esc_html($aftertextstyle); ?>"
		data-thickness		= "4" 
		>
		<div class="d-flex align-items-center">
			<div class="thsn-circle">
				<h4 class="thsn-fid-inner">
				<?php echo thsn_esc_kses( $before_text ); ?>
				<span
					class				  = "thsn-number-rotate"
					data-appear-animation = "animateDigits"
					data-from             = "0"
					data-to               = "<?php echo esc_html( $digit ); ?>"
					data-interval         = "<?php echo esc_html( $interval ); ?>"
					data-before           = ""
					data-before-style     = ""
					data-after            = ""
					data-after-style      = ""
					>
					<?php echo esc_html( $digit ); ?>
				</span><span class="thsn-fid-sub"><?php echo thsn_esc_kses( $after_text ); ?></span>
			</h4>			
			</div>			
			<div class="thsn-circle-inner">
				<span class="thsn-fid-title"><?php echo thsn_esc_kses($title); ?></span>
				<?php echo thsn_esc_kses($desc_html); ?>
			</div>
		</div>
	</div>
</div><!-- .thsn-fld-contents -->
