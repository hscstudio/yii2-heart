$(document).on('click','.modal-heart').on('click', function () {
        var $modal = $('#modal-heart');
        var $link = $(this);
        var $source = $link.attr('source')
        var $size = $link.attr('modal-size')
        if($size=='modal-lg'){
            $modal.find('.modal-dialog').removeClass( "modal-lg modal-md modal-sm" ).addClass( "modal-lg" );
        }
        else if($size=='modal-sm'){
            $modal.find('.modal-dialog').removeClass( "modal-lg modal-md modal-sm" ).addClass( "modal-sm" );
        }
        else{
            $modal.find('.modal-dialog').removeClass( "modal-lg modal-md modal-sm" );
        }

        $modal.find('.modal-refresh').attr('href', $link.attr('href'));
        if ($link.attr('title')) {
            $modal.find('.modal-title').text($link.attr('title'));
        }
        else {
            $modal.find('.modal-title').html($link.attr('modal-title')); // warning: klo attribut title dan modal-title ada 2-2 nya
                                                                         // yang menang bakal yang title.
        }
        $modal.find('.modal-body .content').html('Loading ...');
        $modal.modal('show');

        $.ajax({
            type: 'POST',
            cache: false,
            url: $link.prop('href'),
            data: $('.form-heart form').serializeArray(),
            success: function (data) {
                if ($source)
                    result = $(data).find($source);
                else
                    result = data;
                $modal.find('.modal-body .content').html(result);
                $modal.find('.modal-body .content').css('max-height', ($(window).height() - 200) + 'px');
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $modal.find('.modal-body .content').html('<div class="error">' + XMLHttpRequest.responseText + '</div>');
            }
        });
        return false;
    });
}

$('#modal-heart').on('keydown', function (e) {
	if (e.keyCode === 82) {
		$('.modal-refresh').trigger('click');
	} 
});