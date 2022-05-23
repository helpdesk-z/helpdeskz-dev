$(function () {
    $('select').select2({
        minimumResultsForSearch: -1
    });

    $('.searcher').select2();

    check_all();
});

function redirect(url) {
    location.href = url;
}

function check_all() {
    $("#select_all").on('click', function () {
        if($(this).prop('checked') === true){
            $(".select_item").prop('checked', true);
        }else{
            $(".select_item").prop('checked', false);
        }
    });
}