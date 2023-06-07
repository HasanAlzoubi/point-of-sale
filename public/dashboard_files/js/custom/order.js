$(document).ready(function () {

    //add product btn
    $('.add-product-btn').on('click', function (e) {

        e.preventDefault();
        let name = $(this).data('name');
        let id = $(this).data('id');
        let price = $.number($(this).data('price'), 2);
        let stock = $.number($(this).data('stock'), 2);

        console.log('stock: '+stock)


        $(this).removeClass('btn-success').addClass('btn-default disabled');

        var html =
            `<tr>
                <td class="product-name">${name}</td>
                <td><input type="number" name="products[${id}][quantity]" data-price="${price}" class="form-control input-sm product-quantity" min="1" value="1"></td>
                <td class="product-price">${price}</td>
                <td class="product-stock" hidden>${stock}</td>
                <td><button class="btn btn-danger btn-sm remove-product-btn" data-id="${id}"><span class="fa fa-trash"></span></button></td>
            </tr>`;

        $('.order-list').append(html);

        //to calculate total price
        calculateTotal();
    });

    //disabled btn
    $('body').on('click', '.disabled', function (e) {

        e.preventDefault();

    });//end of disabled

    //remove product btn
    $('body').on('click', '.remove-product-btn', function (e) {

        e.preventDefault();
        var id = $(this).data('id');

        $(this).closest('tr').remove();
        $('#product-' + id).removeClass('btn-default disabled').addClass('btn-success');

        //to calculate total price
        calculateTotal();

    });//end of remove product btn

    //change product quantity
    $('body').on('keyup change', '.product-quantity', function () {

        var quantity = Number($(this).val()); //2
        var unitPrice = parseFloat($(this).data('price').replace(/,/g, '')); //150


        $(this).closest('tr').find('.product-price').html($.number(quantity * unitPrice, 2));
        calculateTotal();

        check_stock_quantity()


    });//end of product quantity change

    //change product-quantity-edit
    $('body').on('keyup change', '.product-quantity-edit', function () {

        // check_stock_quantity_edit()
        let quantity = Number($(this).val()); //2
        let unitPrice = parseFloat($(this).data('price').replace(/,/g, '')); //150


        $(this).closest('tr').find('.product-price').html($.number(quantity * unitPrice, 2));
        calculateTotal();


        check_stock_quantity_edit()
        check_stock_quantity()

    });//end of product quantity change

    //start of show product details
    $('.show-products').on('click', function (e) {

        e.preventDefault();

        $('#loading').css('display', 'flex');

        let url = $(this).data('url');
        let method = $(this).data('method');
        $.ajax({
            url: url,
            type: method,
            success: function (data) {
                $('#loading').css('display', 'none');
                $('#order-product-list').empty();
                $('#order-product-list').append(data);

            }
        })

    });//end of order products click


    //print order
    $(document).on('click', '.print-btn', function () {

        $('#print-area').printThis();

    });//end of click function


});//end of document ready

//calculate the total price
function calculateTotal() {

    var price = 0;

    $('.order-list .product-price').each(function () {

        price += parseFloat($(this).html().replace(/,/g, ''));

    });//end of product price

    $('.total-price').html($.number(price, 2));

    //check if price > 0
    if (price > 0) {

        $('#add-order-form-btn').removeClass('disabled')

    } else {

        $('#add-order-form-btn').addClass('disabled')

    }//end of else


    $('#input-total-price-order').val(price);

}//end of calculate total

//check if quantity of products greater than stock products
function check_stock_quantity() {

    var product_quantity = 0;
    var product_stock = 0;
    $('.order-list .product-quantity').each(function () {

        product_quantity = Number($(this).val());
        product_stock = Number($(this).closest('tr').find('.product-stock').html());
        console.log(product_quantity)
        console.log(product_stock)

        if (product_quantity > product_stock) {
            $('#add-order-form-btn').addClass('disabled')
            $('#check_quantity').removeClass('hidden')
        } else {
            $('#check_quantity').addClass('hidden')
        }
    });


}

//check if quantity of products greater than stock products
function check_stock_quantity_edit() {

    var product_quantity = 0;
    var product_stock = 0;
    var product_quantity_before_edit = 0;

    $('.order-list .product-quantity-edit').each(function () {

        product_quantity = Number($(this).val());
        product_stock = Number($(this).closest('tr').find('.product-stock').html());
        product_quantity_before_edit = Number($(this).closest('tr').find('.quantity-before-edit').html());

        console.log(product_quantity_before_edit);

        if ((product_stock + product_quantity_before_edit) < product_quantity) {
            $('#add-order-form-btn').addClass('disabled')
            $('#check_quantity').removeClass('hidden')
        } else {
            $('#check_quantity').addClass('hidden')
        }
    });
}
