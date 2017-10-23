$( function() {
    $( "#slider-range" ).slider({
      range: true,
      min: 0,
      max: 3000,
      values: [ 0, 3000 ],
      slide: function( event, ui ) {
        $( "#amount" ).val( "" + ui.values[ 0 ] + " - " + ui.values[ 1 ] );
		$( "#price_from" ).val(ui.values[ 0 ]);
		$( "#price_to" ).val(ui.values[ 1 ]);
      }
    });
    $( "#amount" ).val( "" + $( "#slider-range" ).slider( "values", 0 ) +
      " - " + $( "#slider-range" ).slider( "values", 1 ) );
	  
	$('.btn-submit').click(function(){		
		var bookname = $('#bookname').val();
		var author = $('#author').val();
		var publisher = $('#publisher').val();
		var rating = $('#rating').val();
		var price_from = $('#price_from').val();
		var price_to = $('#price_to').val();
		
		$.ajax({
			url : search_ajax.ajax_url,
			type : 'post',
			data : {
				action : 'search_ajax',
				bookname : bookname,
				author : author,
				publisher : publisher,
				rating : rating,
				price_from : price_from,
				price_to : price_to
			},
			success : function( response ) {
				$('.results').html( response );
			}
		});
	});
} );