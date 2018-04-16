<?php  include_once $_SERVER['DOCUMENT_ROOT'] . '/system/paypalConfig.php'; ?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/assets/css/style.css">
    <title>cart page</title>
    <script src="//www.paypalobjects.com/api/checkout.js"></script>
</head>
<body class="cart ">

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/system/view/header.php'; ?>

<div class="container">
    <div class="container__inner">
        <h1>カートの商品一覧</h1>
        <ul class="cartItems">
            <?php display_cart_item($cart_list_info); ?>
        </ul>

        <?php  display_cart_result($purchase_points,$cart_sum_amount_result,$cart_total_fee);?>

    </div>
</div>


<script type="text/javascript">

    var client = {
        sandbox:    '<?php echo(CLIENT_ID)?>'
    };
    var environment = 'sandbox';
    var transaction = {
        transactions: [
            {
                amount: {
                    total:    '15.00',
                    currency: 'USD'
                }
            }
        ]
    };

    function showDom(id) {
        var arr;
        if (!Array.isArray(id)) {
            arr = [id];
        } else {
            arr = id;
        }
        arr.forEach(function (domid) {
            document.getElementById(domid).style.display = 'initial';
        });
    }

    function hideDom(id) {
        var arr;
        if (!Array.isArray(id)) {
            arr = [id];
        } else {
            arr = id;
        }
        arr.forEach(function (domid) {
            document.getElementById(domid).style.display = 'none';
        });
    }

    function handleResponse(result) {
        document.getElementById('confirm').style.display ='none';
        // var resultDOM = document.getElementById('paypal-execute-details').textContent;
        // document.getElementById('paypal-execute-details').textContent = JSON.stringify(result, null, 2);

        var resultDOM = JSON.stringify(result, null, 2);
        console.log(resultDOM);

        $json_response = result;
        // console.log($json_response['id']);
        var payID = $json_response['id'];

        var paymentState = $json_response['state'];
        var finalAmount = $json_response['transactions'][0]['amount']['total'];
        var currency = $json_response['transactions'][0]['amount']['currency'];
        var transactionID= $json_response['transactions'][0]['related_resources'][0]['sale']['id'];
        var payerFirstName = $json_response['payer']['payer_info']['first_name'];
        var payerLastName = $json_response['payer']['payer_info']['last_name'];
        var recipientName= $json_response['payer']['payer_info']['shipping_address']['recipient_name'],FILTER_SANITIZE_SPECIAL_CHARS;
        var addressLine1= $json_response['payer']['payer_info']['shipping_address']['line1'];
        var addressLine2 = $json_response['payer']['payer_info']['shipping_address']['line2'];
        var city= $json_response['payer']['payer_info']['shipping_address']['city'];
        var state= $json_response['payer']['payer_info']['shipping_address']['state'];
        var postalCode =$json_response['payer']['payer_info']['shipping_address']['postal_code'];
        var transactionType = $json_response['intent'];
        // var countryCode= filter_var($json_response['payer']['payer_info']['shipping_address']['country_code'],FILTER_SANITIZE_SPECIAL_CHARS);

        document.getElementById('paypal-execute-details-postal-code').textContent = postalCode;
        document.getElementById('paypal-execute-details-state').textContent = state;
        document.getElementById('paypal-execute-details-recipient-name').textContent = recipientName;
        document.getElementById('paypal-execute-details-transaction-type').textContent = transactionType;
        document.getElementById('paypal-execute-details-transaction-ID').textContent = transactionID;
        document.getElementById('paypal-execute-details-first-name').textContent = payerFirstName;
        // document.getElementById('paypal-execute-details-last-name').textContent = payerLastName;
        document.getElementById('paypal-execute-details-payment-state').textContent = paymentState;
        document.getElementById('paypal-execute-details-final-amount').textContent = finalAmount;
        document.getElementById('paypal-execute-details-currency').textContent = currency;
        document.getElementById('paypal-execute-details-addressLine1').textContent = addressLine1;
        document.getElementById('paypal-execute-details-addressLine2').textContent = addressLine2;
        document.getElementById('paypal-execute-details-city').textContent = city;



        showDom('paypal-end');
        var button = document.getElementById('myContainer');
        // button.link.style.display = 'none';
        var instructionNode = document.getElementById('instruction');
        instructionNode.style.display= 'none';
    }


    paypal.Button.render({

        // Set your environment

        env: 'sandbox', // sandbox | production

        client: {
            sandbox: '<?php echo(CLIENT_ID)?>'
        },


        // Wait for the PayPal button to be clicked

        payment: function(actions) {

            var tax_amt = document.getElementById('tax_amt').value;
            var shipping_discount = document.getElementById('shipping_discount').value;
            var handling_fee = document.getElementById('handling_fee').value;
            var insurance_fee = document.getElementById('insurance_fee').value;
            // var shippingSel = document.getElementById('shipping_method');
            var shipping_amt = document.getElementById('shipping_amt').value;

            var total_amt = document.getElementById('total_amt').value;

            return actions.payment.create({
                meta: {
                    partner_attribution_id: '<?php echo(SBN_CODE)?>'
                },
                payment: {
                    transactions: [
                        {
                            amount: {
                                total: total_amt ,
                                currency: 'USD',
                                details:
                                    {
                                        subtotal: total_amt - shipping_amt,
                                        shipping: shipping_amt,
                                    }
                            }
                        }
                    ]
                }
            });


        },

        // Wait for the payment to be authorized by the customer

        onAuthorize: function(data, actions) {

            return actions.payment.get().then(function(data) {

                var currentShippingVal = data.transactions[0].amount.details.shipping;
                var shipping = data.payer.payer_info.shipping_address;

                var currentTotal = data.transactions[0].amount.total;

                document.querySelector('#recipient').innerText = shipping.recipient_name;
                document.querySelector('#line1').innerText     = shipping.line1;
                document.querySelector('#city').innerText      = shipping.city;
                document.querySelector('#state').innerText     = shipping.state;
                document.querySelector('#zip').innerText       = shipping.postal_code;
                document.querySelector('#country').innerText   = shipping.country_code;

                var updatedShipping = parseInt(currentShippingVal) + parseInt(2);
                document.querySelector('#shipping_amt_updated').innerText = updatedShipping;

                console.log('Updated Shipping : '+ updatedShipping);

                //total_amt =+ total_amt + shipping_amt_updated;

                document.querySelector('#myContainer').style.display = 'none';
                document.querySelector('#confirm').style.display = 'block';

                // Listen for click on confirm button

                document.querySelector('#confirmButton').addEventListener('click', function() {

                    // Disable the button and show a loading message

                    document.querySelector('#confirm').innerText = 'Loading...';
                    document.querySelector('#confirm').disabled = true;

                    // Execute the payment

                    var totalAmount = currentTotal - parseInt(updatedShipping);
                    var subtotal = currentTotal - parseInt(updatedShipping);

                    return actions.payment.execute(
                        {
                            transactions: [
                                {
                                    amount: {
                                        total: totalAmount,
                                        currency: 'USD',
                                        details:
                                            {
                                                subtotal: subtotal,
                                                shipping: updatedShipping,
                                            }
                                    }
                                }
                            ]
                        }).then(handleResponse);

                })

                // return actions.payment.execute().then(handleResponse);
            })
        }

    }, '#myContainer');
</script>
<!--<script>-->
<!--    paypal.Button.render({-->
<!--        env: 'sandbox', // Or 'sandbox',-->
<!---->
<!--        commit: true, // Show a 'Pay Now' button-->
<!---->
<!--        style: {-->
<!--            color: 'gold',-->
<!--            size: 'small'-->
<!--        },-->
<!---->
<!--        payment: function(data, actions) {-->
<!--            /*-->
<!--             * Set up the payment here-->
<!--             */-->
<!--        },-->
<!---->
<!--        onAuthorize: function(data, actions) {-->
<!--            /*-->
<!--             * Execute the payment here-->
<!--             */-->
<!--        },-->
<!---->
<!--        onCancel: function(data, actions) {-->
<!--            /*-->
<!--             * Buyer cancelled the payment-->
<!--             */-->
<!--        },-->
<!---->
<!--        onError: function(err) {-->
<!--            /*-->
<!--             * An error occurred during the transaction-->
<!--             */-->
<!--        }-->
<!--    }, '#paypal-button');-->
<!--</script>-->

</body>
</html>