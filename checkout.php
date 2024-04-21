<?php  include("includes/header.php");?>
<!DOCTYPE html>
<html>
  
<!-- Mirrored from demo.templatesjungle.com/ministore/checkout.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 26 Mar 2024 19:59:50 GMT -->

    <section class="hero-section position-relative bg-light-blue padding-medium">
      <div class="hero-content">
        <div class="container">
          <div class="row">
            <div class="text-center padding-large no-padding-bottom">
              <h1 class="display-2 text-uppercase text-dark">Checkout</h1>
              <div class="breadcrumbs">
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="shopify-cart checkout-wrap padding-large">
      <div class="container">
        <form class="form-group">
          <div class="row d-flex flex-wrap">
            <div class="col-lg-6">
              <h2 class="display-7 text-uppercase text-dark pb-4">Billing Details</h2>
              <div class="billing-details">
                <label for="fname">First Name*</label>
                <input type="text" id="fname" name="firstname" class="form-control mt-2 mb-4 ps-3">
                <label for="lname">Last Name*</label>
                <input type="text" id="lname" name="lastname" class="form-control mt-2 mb-4 ps-3">
                <label for="cname">Country / Region*</label>
                <select class="form-select form-control mt-2 mb-4" aria-label="Default select example">
                  <option selected="" hidden="">United States</option>
                  <option value="1">UK</option>
                  <option value="2">Australia</option>
                  <option value="3">Canada</option>
                </select>
                <label for="city">Town / City *</label>
                <input type="text" id="city" name="city" class="form-control mt-3 ps-3 mb-4">
                <label for="address">Address*</label>
                <input type="text" id="adr" name="address" placeholder="House number and street name" class="form-control mt-3 ps-3 mb-3">
                <input type="text" id="adr" name="address" placeholder="Appartments, suite, etc." class="form-control ps-3 mb-4">
                <label for="email">Phone *</label>
                <input type="text" id="phone" name="phone" class="form-control mt-2 mb-4 ps-3">
                <label for="email">Email address *</label>
                <input type="text" id="email" name="email" class="form-control mt-2 mb-4 ps-3">
              </div>
            </div>
            <div class="col-lg-6">
              <h2 class="display-7 text-uppercase text-dark pb-4">Additional Information</h2>
              <div class="billing-details">
                <label for="fname">Order notes (optional)</label>
                <textarea class="form-control pt-3 pb-3 ps-3 mt-2" placeholder="Notes about your order. Like special notes for delivery."></textarea>
              </div>
              <div class="your-order mt-5">
                <h2 class="display-7 text-uppercase text-dark pb-4">Cart Totals</h2>
                <div class="total-price">
                  <table cellspacing="0" class="table">
                    <tbody>
                      <tr class="subtotal border-top border-bottom pt-2 pb-2 text-uppercase">
                        <th>Subtotal</th>
                        <td data-title="Subtotal">
                          <span class="price-amount amount text-primary ps-5">
                            <bdi>
                              <span class="price-currency-symbol">$</span>2,370.00 </bdi>
                          </span>
                        </td>
                      </tr>
                      <tr class="order-total border-bottom pt-2 pb-2 text-uppercase">
                        <th>Total</th>
                        <td data-title="Total">
                          <span class="price-amount amount text-primary ps-5">
                            <bdi>
                              <span class="price-currency-symbol">$</span>2,370.00 </bdi>
                          </span>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="list-group mt-5 mb-3">
                    <label class="list-group-item d-flex gap-2 border-0">
                      <input class="form-check-input flex-shrink-0" type="radio" name="listGroupRadios" id="listGroupRadios1" value="" checked>
                      <span>
                        <strong class="text-uppercase">Direct bank transfer</strong>
                        <small class="d-block text-body-secondary">Make your payment directly into our bank account. Please use your Order ID. Your order will shipped after funds have cleared in our account.</small>
                      </span>
                    </label>
                    <div id="bank-details">
                      <input type="text" id="provider" placeholder="Provider">
                      <input type="text" id="account_number" placeholder="Account Number">
                      <input type="text" id="expiry_date" placeholder="Expiry Date">
                    </div>
                    <script>
                      document.addEventListener("DOMContentLoaded", function() {
                        var radios = document.getElementsByName('listGroupRadios');
                        var bankDetails = document.getElementById('bank-details');
                    
                        for (var i = 0; i < radios.length; i++) {
                          radios[i].addEventListener('change', function() {
                            if (this.id === 'listGroupRadios1') {
                              bankDetails.style.display = 'block';
                            } else {
                              bankDetails.style.display = 'none';
                            }
                          });
                        }
                      });
                    </script>
                    <label class="list-group-item d-flex gap-2 border-0">
                      <input class="form-check-input flex-shrink-0" type="radio" name="listGroupRadios" id="listGroupRadios3" value="">
                      <span>
                        <strong class="text-uppercase">Cash on delivery</strong>
                        <small class="d-block text-body-secondary">Pay with cash upon delivery.</small>
                      </span>
                    </label>
                  </div>
                  <button type="submit" name="submit" class="btn btn-dark btn-medium text-uppercase btn-rounded-none" style="margin-left: 200px;">Place an order</button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </section>
    <br>
    <br>
    <?php include("includes/footer.php")?>
    <script src="js/jquery-1.11.0.min.js"></script>
    <script src="cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="js/plugins.js"></script>
    <script type="text/javascript" src="js/script.js"></script>
  </body>

<!-- Mirrored from demo.templatesjungle.com/ministore/checkout.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 26 Mar 2024 19:59:50 GMT -->
</html>
