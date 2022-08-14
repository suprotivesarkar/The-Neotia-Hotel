<?php
include '_top.php';
if (!isset($_SESSION['bookin']) OR empty($_SESSION['bookin'])) {
    header("Location:./");   
    die(); 
} 
$razorpaykey= Razorpaykey()['key'];
$amount = $_SESSION['bookin'][0]['amount'];
$bookingID = $_SESSION['bookin'][0]['bookingID'];
$name = $_SESSION['bookin'][0]['name'];
$email = $_SESSION['bookin'][0]['email'];
$phone = $_SESSION['bookin'][0]['phone'];
?> 
<link rel="icon" type="image/png" href="images/fav.png">
<div class="pay"><center><img src="images/payment1.gif"></center></div>
<style type="text/css">
.pay{display:none}
.pay.isloading{display:block}
</style>
<button id="rzp-button1" hidden="">Pay</button>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="assets/js/jquery.min.js"></script>
<script>
var options = {
    "key": "<?= $razorpaykey?>", 
    "amount": "<?php echo $amount; ?>", 
    "currency": "INR",
    "name": "The Neotia Hotel",
    "order_id": "<?= $bookingID; ?>",
    "description": "BOOKING",
    "handler": function (response){
        $.ajax({
            url: 'payment-check', 
            type: 'post',
            dataType: 'json',
            data:response,  
            beforeSend:function(){$(".pay").addClass("isloading")},
            success: function (res) {
                $(".pay").removeClass("isloading")
                if(res.status){window.location.replace(res.msg);}
                else{alert(res.msg);}
            }
        }); 
    },
    "prefill": {
        "email": "<?php echo $email; ?>",
        "phone": "<?php echo $phone; ?>"
    },
    "modal": {
        "ondismiss": function(){
            window.location.replace("booking");
        }
    },
};
var rzp1 = new Razorpay(options);
window.onload =function(){
    document.getElementById('rzp-button1').click();
}
document.getElementById('rzp-button1').onclick = function(e){
    rzp1.open();
    e.preventDefault();
}
</script>