(function( $ ){
$.fn.phpdiffmerge = function() {
    function cloneArr(obj) {
        var result = [];
        for (var key in obj) {
            result[key] = obj[key];
        }
        return result;
    }

    var $changes = this.find('.ChangeReplace')
        .add(this.find('.ChangeDelete'))
        .add(this.find('.ChangeInsert')),
        toResolve = $changes.length;

    // var lineOffset = {};
    // var stLnOs = function (line, i) {
    //     if (typeof lineOffset[line] === 'undefined') {
    //         lineOffset[line] = 0;
    //     }
    //     lineOffset[line] += i;
    //     console.log('lineOffset changed', lineOffset[line]);
    // };
    // var ckLnOs = function (line) {
    //     line = parseInt(line);
    //     $.each(lineOffset, function(k, v) {
    //         if (k < line) {
    //             line += parseInt(v);
    //         }
    //     });
    //     return line;
    // }

    $changes.each(function() {
        var $thiz = $(this);

        $thiz.find('td').click(function() {
            var u = 'Right',
                d = 'Left';


            if ($(this).hasClass('Left')) {
                u = 'Left';
                d = 'Right';
            }

            $thiz.find('td.'+u).removeClass('dontUse').addClass('use');
            $thiz.find('td.'+d).removeClass('use').addClass('dontUse');
            toResolve--;
            if (toResolve === 0) {
                $('#merge').addClass('btn-success');
            }

        }).hover(function() {
            var h = 'Right';
            if ($(this).hasClass('Left')) {
                h = 'Left';
            }
            $thiz.find('td.'+h).addClass('hover');
        }, function() {
            $thiz.find('td').removeClass('hover');
        });
    });

    $('#merge').click(function(e) {
        var end = cloneArr(fullleft),
            lineOffset = 0;

        e.preventDefault();
        if (!$(this).hasClass('btn-success')) {
            return false;
        }


        $changes.each(function() {
            var $change = $(this),
            $c = $change.find('.use').first();

            if ($c.hasClass('Left')) {
                return;
            }

            var $prv = $change.prev('tbody').find('tr').last(),
                leftLine = parseInt($prv.find('th').first().html()),
                rightLine = parseInt($prv.find('th').last().html()),
                rows = $change.find('td.Left').length;

            if (isNaN(leftLine)) {
                leftLine = 0;
            }
            if (isNaN(rightLine)) {
                rightLine = 0;
            }

            if ($change.hasClass('ChangeReplace')) {
                var rowsLeft = 0;
                $change.find('.Left').each(function() {
                    if ($(this).prev('th').html() !== '&nbsp;') {
                        rowsLeft++;
                    }
                });
                var rowsRight = 0;
                $change.find('.Right').each(function() {
                    if ($(this).prev('th').html() !== '&nbsp;') {
                        rowsRight++;
                    }
                });

                var tmpoffset = lineOffset;

                for (var i = 0; i < rowsLeft; i++) {
                    end.splice(leftLine+tmpoffset, 1);
                }
                tmpoffset -= rowsLeft;

                var tmpoffset = lineOffset;
                for (var i = 0; i < rowsRight; i++) {
                    end.splice(leftLine+i+tmpoffset, 0, fullright[rightLine+i]);
                }

                lineOffset = lineOffset+(rowsRight-rowsLeft);
            } else if ($change.hasClass('ChangeDelete') || $change.hasClass('ChangeInsert')) {
                if ($c.hasClass('Left')) {
                    return;
                }

                if ($change.hasClass('ChangeInsert')) {
                    for (var i = 0; i < rows; i++) {
                        end.splice(leftLine+i+lineOffset, 0, fullright[rightLine+i]);
                        lineOffset++;
                    }
                } else if($change.hasClass('ChangeDelete')) {

                    for (var i = 0; i < rows; i++) {
                        end.splice(leftLine+i+lineOffset, 1);
                    }
                    lineOffset -= rows;
                    console.log(lineOffset);
                }

                // var m = $c.hasClass('Left'), // mode
                //     n = fullleft, // new Content
                //     l = leftLine, // Line
                //     d = $change.hasClass('ChangeDelete'); // delete

                // if (!d) {
                //     m = !m;
                //     n = fullright;
                //     l = rightLine;
                // }

                // // var rm = ckLnOs(l), //remove line
                // var rm = l+lineOffset; //remove line

                // for (var i = rows; i > 0; i--) {
                //     console.log(l);
                //     var el = l+lineOffset;
                //     if (m) {
                //         if (end[el] !== n[l]) {
                //             end.splice(el, 0, n[l]);
                //             if (!d) {
                //                 lineOffset++;
                //                 console.log(lineOffset);
                //             }
                //         }
                //     } else if(!m) {
                //         if (end[rm] === n[l]) {
                //             end.splice(rm, 1);
                //             if (!d) {
                //                 lineOffset--;
                //                 console.log(lineOffset);
                //             }
                //         }
                //     }
                //     l++;
                // }

                // if (changed && $c.hasClass('Right')) {
                //     $thiz.addClass('right');
                //     if (d) {
                //         stLnOs(rm, -rows);
                //     } else {
                //         stLnOs(rm, rows);
                //     }
                // } else if (changed &&  $thiz.hasClass('right')) {
                //     $thiz.removeClass('right');
                //     if (d) {
                //         stLnOs(rm, rows);
                //     } else {
                //         stLnOs(rm, -rows);
                //     }
                // }
                // if (changed && !d && m) {
                // } else if (changed && !d && !m) {
                //     stLnOs(rm, -rows);
                // }
            } 
            // console.log(lineOffset);
        });
        console.log(end);
        // $('body').html('<pre>'+end.join("\n")+'</pre>');
    });

    // console.log(fullleft);
};
})(jQuery);



jQuery(document).ready(function($) {
    $('.Differences').phpdiffmerge();
});