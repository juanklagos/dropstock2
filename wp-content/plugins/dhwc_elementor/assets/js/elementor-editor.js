(function($){
	'use strict';
	if ( window.elementor ) {
		elementor.hooks.addAction( 'panel/open_editor/widget', function( panel, model, view ) {
			var $this = $(panel.$el).find('.dhwc-elementor-ajaxselect2'),
				id = $this.data('setting'),
				options = $(view.$el).find('#'+id+'_select2_data').data('select2_data');
			
			if($this.length){
				$this.select2({
					minimumInputLength: 3,
					escapeMarkup: function( m ) {
						return m;
					},
					data:options,
					ajax: {
						url:         dhwc_elementor_editor_params.ajax_url,
						dataType:    'json',
						delay:       250,
						data:        function( params ) {
							return {
								term:     params.term,
								action:   'dhwc_elementor_ajax_select2_search_products'
							};
						},
						processResults: function( data ) {
							var terms = [];
							if ( data ) {
								$.each( data, function( id, text ) {
									terms.push( { id: id, text: text } );
								});
							}
							return {
								results: terms
							};
						},
						cache: true
					}
				})
			}
		})
	}
})(jQuery)