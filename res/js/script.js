jQuery(document).ready(function($) {

    if ($('.Differences').length) {
        for (var i = 0; i < 2; i++) {
            var $h = $('.Differences thead th'),
                s = (i === 0 ? '#leftfile' : '#rightfile');
            $h = (i === 0 ? $h.first() : $h.last());

            $h.html('<span class="name">'+$(s).html()+'</span>'+
                '<br /><span class="time muted">Modified: '+$(s).attr('data-modified')+
                '<br />On client: '+$(s).attr('data-client_mtime')+'</span>'
            );
        };

        var $Diff = $('.Differences').phpdiffmerge({
        	left: fullleft,
        	right: fullright,
        	// pupupResult: true,
        	// debug: true,
            button: '#merge',
        	merged: function(merge) {
                $.post(
                    window.location,
                    {
                        action: 'merge',
                        merge: merge,
                        name: $('input.filename:checked').val(),
                        currentFiles: currentFiles
                    }, function(r) {
                        r = eval('('+r+')');
                        if (r.status === 'OK') {
                            window.location.href=window.location.href;
                        }
                    }
                );
        	}
        });

        $('#right').click(function(e) {
            e.preventDefault();
            $Diff.useRight();
        });

        $('#left').click(function(e) {
            e.preventDefault();
            $Diff.useLeft();
        });
    }
});