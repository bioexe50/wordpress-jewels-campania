<div class="thsn-fld-contents">
	<?php echo thsn_esc_kses( $icon ); ?>
	<div class="thsn-fld-wrap">
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
			</span>
			<span class="thsn-fid-sub"><?php echo thsn_esc_kses( $after_text ); ?></span>
		</h4>
		<?php if( !empty($title) ) : ?>
		<span class="thsn-fid-title"><span><?php echo thsn_esc_kses($title); ?><br></span></span>
		<?php endif; ?>
	</div>
</div><!-- .thsn-fld-contents -->
