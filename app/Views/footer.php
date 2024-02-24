<!--==========================
    Footer
  ============================-->
<footer id="footer" class="section-bg">
  <div class="footer-top">
    <div class="container">

      <div class="row">

        <div class="col-lg-6">

          <div class="row">

            <div class="col-sm-6">

              <div class="footer-info">
                <h3 class="f-20">TrueTechnologies</h3>
                <!-- <p>Cras fermentum odio eu feugiat lide par naso tierra. Justo eget nada terra videa magna derita valies darta donna mare fermentum iaculis eu non diam phasellus. Scelerisque felis imperdiet proin fermentum leo. Amet volutpat consequat mauris nunc congue.</p> -->
              </div>

              <div class="footer-links">
                <h4>Contact Us</h4>
                <p>
                  TRUE TECHNOLOGIES <br>
                  Krishti Plaza <br>
                  Kalipark, Bablatala, <br>
                  Gopalpur I, Kolkata-156<br>
                  West Bengal<br>
                  <strong>Phone:</strong> +91 6290 660 442<br>
                  <strong>Email:</strong> admin@findsoftware4u.com<br>
                </p>
              </div>

            </div>

            <div class="col-sm-6">
              <div class="footer-links">
                <h4>Useful Links</h4>
                <ul>
                  <li><a href="#">Home</a></li>
                  <li><a href="#">Services</a></li>
                  <li><a href="#">Portfolio</a></li>
                  <li><a href="#">Demo</a></li>
                </ul>
              </div>



              <div class="social-links">
                <a href="#" class="twitter"><i class="fa fa-twitter"></i></a>
                <a href="#" class="facebook"><i class="fa fa-facebook"></i></a>
                <a href="#" class="instagram"><i class="fa fa-instagram"></i></a>
                <a href="#" class="linkedin"><i class="fa fa-linkedin"></i></a>
              </div>

            </div>

          </div>

        </div>

        <div class="col-lg-6">

          <div class="form">

            <h4>Send us a message</h4>
            <form action="<?php echo base_url(); ?>telenix/daily-mis-entry/insert-touch" method="post" role="form" class="contactForm">
              <div class="form-group">
                <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" data-rule="minlen:4" data-msg="Please enter at least 4 chars" />
                <div class="validation"></div>
              </div>
              <div class="form-group">
                <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" data-rule="email" data-msg="Please enter a valid email" />
                <div class="validation"></div>
              </div>
              <div class="form-group">
                <input type="tel" pattern="[7-9]{1}[0-9]{9}" class="form-control" name="phn" id="phn" placeholder="Phone Number" data-rule="minlen:4" data-msg="Please enter valid Mobile Number" />
                <div class="validation"></div>
              </div>
              <div class="form-group">
                <textarea class="form-control" name="message" rows="5" data-rule="required" data-msg="Please write something for us" placeholder="Message"></textarea>
                <div class="validation"></div>
              </div>

              <div id="sendmessage">Your message has been sent. Thank you!</div>
              <div id="errormessage"></div>

              <div class="text-center"><button type="submit" title="Send Message" id="submit">Send Message</button></div>
            </form>
          </div>

        </div>



      </div>

    </div>
  </div>
  <!-- <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Scan QR Code</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <img class="img-fluid" src="https://truetechnologies.in/assets/img/True Technology.png" alt="">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div> -->
  <div class="container">
    <div class="copyright">
      &copy; Copyright <strong>TrueTechnology</strong>. All Rights Reserved
    </div>
    <div class="credits">
      <!--
          All the links in the footer should remain intact.
          You can delete the links only if you purchased the pro version.
          Licensing information: https://bootstrapmade.com/license/
          Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/buy/?theme=Rapid
        -->
      Designed by <a href="http://findsoftware4u.com/">TrueTechnology</a>
    </div>
  </div>
</footer><!-- #footer -->

<a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
<!-- Uncomment below i you want to use a preloader -->
<!-- <div id="preloader"></div> -->
<script src="https://platform.linkedin.com/badges/js/profile.js" async defer type="text/javascript"></script>
<!--Profile Badge-->
<!-- JavaScript Libraries -->
<script src="<?php echo base_url(); ?>assets/lib/jquery/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/lib/jquery/jquery-migrate.min.js"></script>
<script src="<?php echo base_url(); ?>assets/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url(); ?>assets/lib/easing/easing.min.js"></script>
<script src="<?php echo base_url(); ?>assets/lib/mobile-nav/mobile-nav.js"></script>
<script src="<?php echo base_url(); ?>assets/lib/wow/wow.min.js"></script>
<script src="<?php echo base_url(); ?>assets/lib/waypoints/waypoints.min.js"></script>
<script src="<?php echo base_url(); ?>assets/lib/counterup/counterup.min.js"></script>
<script src="<?php echo base_url(); ?>assets/lib/owlcarousel/owl.carousel.min.js"></script>
<script src="<?php echo base_url(); ?>assets/lib/isotope/isotope.pkgd.min.js"></script>
<script src="<?php echo base_url(); ?>assets/lib/lightbox/js/lightbox.min.js"></script>
<!-- Contact Form JavaScript File -->
<script src="<?php echo base_url(); ?>assets/contactform/contactform.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/ionicons/5.5.0/esm/ionicons.min.js" integrity="sha512-YR5ochxRyT+6+k+Gevpn46Jrzq698AmDkKR5xcQRLRtiWDUhvOdeZyIXSOw1V5orFrOVNg2DSJtiqoZU7fNRPQ==" crossorigin="anonymous"></script> -->
<!-- Template Main Javascript File -->
<script src="<?php echo base_url(); ?>assets/js/main.js"></script>

</body>

</html>