$(document).ready(function () {
    function updateStriping()
    {
        let index = 0;
        $('#sortedStrippedTable > tbody  > tr').each(function (i, row) {
            $(row).removeClass('odd');
            if ($(row).hasClass('tablesorter-hasChildRow')) {
                index++;
            }
            if ( index % 2 === 1 &&  $(row).hasClass('tablesorter-hasChildRow')) {
                $(row).addClass('odd');
            }
        });
    }

    function updateArrows()
    {
        $('#sortedStrippedTable > thead  > tr > th').each(function (i, column) {
            if ($(column).hasClass('tablesorter-headerAsc')) {
                $(column).children('div').children('i').removeClass('fa-sort');
                $(column).children('div').children('i').removeClass('fa-sort-down');

                $(column).children('div').children('i').addClass('fa-sort-up')
            } else if ($(column).hasClass('tablesorter-headerDesc')) {
                $(column).children('div').children('i').removeClass('fa-sort-up');
                $(column).children('div').children('i').removeClass('fa-sort');

                $(column).children('div').children('i').addClass('fa-sort-down')

            } else {
                $(column).children('div').children('i').removeClass('fa-sort-down');
                $(column).children('div').children('i').removeClass('fa-sort-up');

                $(column).children('div').children('i').addClass('fa-sort');
            }

        });
    }


    $('.read-more').click(function () {

        $(this).closest('tr').next('tr').toggle(250);
        updateStriping();
        if ($(this).children('i').hasClass('fa-angle-down')) {
            $(this).children('i').removeClass('fa-angle-down');
            $(this).children('i').addClass('fa-angle-up');
        } else {
            $(this).children('i').addClass('fa-angle-down');
            $(this).children('i').removeClass('fa-angle-up');
        }
    });

    $(".tablesorter")
        .tablesorter()
        .bind("sortStart", function () {

        })
        .bind("sortEnd", function () {
            updateStriping();
            updateArrows();


        });

    $('.tablesorter').delegate('.toggle', 'click' , function () {
        // use "nextUntil" to toggle multiple child rows
        // toggle table cells instead of the row
        $(this).closest('tr').nextUntil('tr:not(.tablesorter-childRow)').find('td').toggle();

        return false;
    });

    updateStriping();
});



