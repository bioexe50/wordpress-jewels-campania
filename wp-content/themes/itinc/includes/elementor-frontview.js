jQuery( window ).on( 'elementor/frontend/init', function() {
	elementorFrontend.hooks.addAction( 'frontend/element_ready/thsn_blog_element.default', function($scope, $){ thsn_carousel(); });
	elementorFrontend.hooks.addAction( 'frontend/element_ready/thsn_portfolio_element.default', function($scope, $){ thsn_carousel(); });
	elementorFrontend.hooks.addAction( 'frontend/element_ready/thsn_service_element.default', function($scope, $){ thsn_carousel(); });
	elementorFrontend.hooks.addAction( 'frontend/element_ready/thsn_team_element.default', function($scope, $){ thsn_carousel(); });
	elementorFrontend.hooks.addAction( 'frontend/element_ready/thsn_testimonial_element.default', function($scope, $){ thsn_carousel(); });
	elementorFrontend.hooks.addAction( 'frontend/element_ready/thsn_client_element.default', function($scope, $){ thsn_carousel(); });
	elementorFrontend.hooks.addAction( 'frontend/element_ready/thsn_staticbox_element.default', function($scope, $){ thsn_carousel(); });
	elementorFrontend.hooks.addAction( 'frontend/element_ready/thsn_multiple_icon_heading.default', function($scope, $){ thsn_carousel(); });
	elementorFrontend.hooks.addAction( 'frontend/element_ready/thsn_fid_element.default', function($scope, $){ thsn_circle_progressbar(); });
	elementorFrontend.hooks.addAction( 'frontend/element_ready/global', function( $scope, $ ) {
		setTimeout(function(){
			thsn_rearrange_stretched_col( $scope.data('id') );
			thsn_bgimage_class();
			thsn_bgcolor_class();
		}, 200);
	} );
} );

