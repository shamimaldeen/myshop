
(function(window, document, $) {
	'use strict';

	/*******	Bootstrap DateTime Picker	*****/

	// Simple Date time picker
	$('#datetimepicker1').datetimepicker({
		'format' : 'YYYY-MM-DD HH:mm:ss'
	});

    $('#datetimepicker2').datetimepicker({
        'format' : 'YYYY-MM-DD'
    });

	$( '.daterange' ).daterangepicker({
		locale: {
			format: 'YYYY-MM-DD',
			"separator": " / "
		}
	});


})(window, document, jQuery);