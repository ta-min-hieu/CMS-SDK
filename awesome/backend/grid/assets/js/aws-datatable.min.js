
function loadDataTableSortAction() {
    $('body').on('click', '.dataTable th.sorting', function (e) {
        var url = $(this).children().first().attr('href');
        if (url) {
            $.pjax({url: url, timeout: false, container: '#mainGridPjax', push: false});
        }
        return false;
    }).on('click', '.dataTable th.sorting_asc', function (e) {
        var url = $(this).children().first().attr('href');
        if (url) {
            $.pjax({url: url, timeout: false, container: '#mainGridPjax', push: false});
        }
        return false;
    }).on('click', '.dataTable th.sorting_desc', function (e) {
        var url = $(this).children().first().attr('href');
        if (url) {
            $.pjax({url: url, timeout: false, container: '#mainGridPjax', push: false});
        }
        return false;
    });
    if ($.floatThead) {
        var $table = $('table.dataTable');
        $table.floatThead({
            top: 50,
            position: 'fixed',
            "floatTableClass": "aws-table-float",
            "floatContainerClass": "aws-thead-float",
            responsiveContainer: function ($table) {
                return $table.closest('.table-responsive');
            }
        });
    }
}
(function () {
    loadDataTableSortAction();
    $(document).on('pjax:complete', function () {
        if ($.floatThead) {
            var $table = $('table.dataTable');
            if ($table && $table.html()) {
                $table.floatThead({
                    top: 50,
                    position: 'fixed',
                    "floatTableClass": "aws-table-float",
                    "floatContainerClass": "aws-thead-float",
                    responsiveContainer: function ($table) {
                        return $table.closest('.table-responsive');
                    }
                });
            }
        }
    });
})(window.jQuery);
