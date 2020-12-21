(function($){
	'use strict';
	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/dhwc_elementor_single_product_images.default', function( $scope ) {
			if($.isFunction( $.fn.wc_product_gallery ) ){
				$scope.find( '.woocommerce-product-gallery' ).each( function() {
					$( this ).wc_product_gallery();
				} );
			}
			if($.isFunction( $.fn.dhwc_elementor_product_gallery ) ){
				$scope.find( '.dhwc-elementor-product-gallery' ).each( function() {
					$( this ).dhwc_elementor_product_gallery();
				} );
			}
		});
		
		elementorFrontend.hooks.addAction( 'frontend/element_ready/dhwc_elementor_single_product_data_tabs.default', function( $scope ) {
			console.log($scope)
			if($.isFunction( $.fn.wc_product_gallery ) ){
				$scope.find( '.wc-tabs-wrapper, .woocommerce-tabs, #rating' ).trigger( 'init' );
			}
		});
		
		elementorFrontend.hooks.addAction( 'frontend/element_ready/dhwc_elementor_checkout_coupon.default', function( $scope ) {
			var coupon_info=$scope.find("div.woocommerce-form-coupon-toggle > .woocommerce-info");
			console.log($scope)
			coupon_info.removeClass("woocommerce-info")
		});
	})
})(jQuery)