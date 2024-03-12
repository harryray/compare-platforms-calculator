(function( $ ) {
	'use strict';

	/*-----------------------------------------------------------------------------------*/
	/*	Save vendor data
	/*-----------------------------------------------------------------------------------*/

	$(document).on('change', 'select', cplat_form_changed);
	$(document).on('change keypress', 'input', cplat_form_changed);
	function cplat_form_changed() {
		$('.save-vendor-data').addClass('save-btn');
		$('.save-vendor-data').removeClass('saved-btn');
		$('.save-vendor-data span').text("Save");
	}

	$(document).on('change', '.platform-data select', function (e) {
		var optionSelected = $("option:selected", this);
		var symb = $(this).parent().parent().find('.number-value .symbol');

		if ( optionSelected.val() === 'ad_valorem' ) {
			$.each(symb, function() {
				$(this).text("%");
			});
		} else {
			$.each(symb, function() {
				$(this).text("£");
			});
		}
	});

	$(document).on( 'click', '.save-vendor-data', save_vendor_data );
	function save_vendor_data(e) {
		e.preventDefault();
		var $this = $(this);

		$.ajax({
			beforeSend: function () {
				$this.addClass('saving-btn');
				$this.find('span').text("Saving...");
				$this.removeClass('save-btn');
			},
			complete: function () {
				$this.removeClass('saving-btn');
				$this.addClass('saved-btn');
				$this.find('span').text("Saved");
			},
			type: "POST",
			url: get_cplat_vars.ajaxurl,
			data: {
				action: "cplat_save_platform_data",
				data: $('#platform-data').serialize(),
				get_cplat_nonce: get_cplat_vars.get_cplat_nonce
			},
			dataType : 'json',
			success: function(result) {
				if (result.updated === true) {
					$('#version_id').val(result.new_version_id);
				}

			}
		});
	}

})( jQuery );
