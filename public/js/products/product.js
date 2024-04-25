$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('change','#product_type',function(e){
        e.preventDefault();
        let productType = $(this).val();
        if (productType==='gs1') {
            $('.gs1').show();
        }else{
            $('.gs1').hide();
        }
    })
});
