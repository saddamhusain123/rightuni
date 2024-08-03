
<!-- Footer Start -->
      <footer class="footer section-padding section-bg" style="background-image: url(assets/images/bg/footer.jpg);">
         <div class="container">
            <div class="row">
               <!-- item -->
               <div class="col-lg-3 col-sm-6">
                  <div class="ft_widgets">
                     <div class="ft_about">
                        <div class="ft_logo">
                           <img src="assets/images/RightUNI-logo.png" alt="Rightuni" class="image-fit-contain">
                        </div>
                        <p>Rightuni is India's largest Higher Education Ecosystem. Rightuni guides students to reach the right college and help colleges to teach them better.</p>
                        <ul class="ft_social">
                           <li>
                              <a href="https://www.facebook.com/profile.php?id=100091637054207">
                              <i class="fab fa-facebook-f"></i>
                              </a>
                           </li>
                           <li>
                              <a href="https://www.instagram.com/right_uni/">
                              <i class="fab fa-instagram"></i>
                              </a>
                           </li>
                        </ul>
                     </div>
                  </div>
               </div>
               <!-- item -->
               <div class="col-lg-3 col-sm-6">
                  <div class="ft_widgets">
                     <h5 class="ft_title">Useful Links</h5>
                     <ul class="ft_menu">
                        <li>
                           <a href="home">Home</a>
                        </li>
                        <li>
                           <a href="blogs">Blog</a>
                        </li>
                        <li>
                           <a href="universities">University</a>
                        </li>
                        <li>
                           <a href="contact">Contact</a>
                        </li>
                     </ul>
                  </div>
               </div>
               <!-- item -->
               <div class="col-lg-3 col-sm-6">
                  <div class="ft_widgets">
                     <h5 class="ft_title">Popular Courses</h5>
                     <ul class="ft_menu">
                     <?php 
                     $sql = "SELECT id, name, slug, status FROM courses WHERE status = 1 AND deleted = 0 ORDER BY id DESC LIMIT 5";
                     $result = $conn->query($sql);

                     if ($result->num_rows > 0) {
                        while ($course_data = $result->fetch_assoc()) {
                           $cid = htmlspecialchars($course_data['id']);
                           $course_name = htmlspecialchars($course_data['name']);
                           echo "<li><a href=\"colleges/$cid\">$course_name</a></li>";
                        }
                     } else {
                        echo "<li>No courses available</li>";
                     }
                     ?>
                     </ul>

                  </div>
               </div>
               <!-- item -->
               <div class="col-lg-3 col-sm-6">
                  <div class="ft_widgets">
                     <h5 class="ft_title">Contact Us</h5>
                     <p>Get in touch with us for inquiries, support, or feedback. We’re here to assist you with any questions.</p>
                     <ul class="ft_contact ft_menu">
                        <li>
                           <a href="tel:(+91)97999 46027">
                           <i class="fas fa-phone-volume"></i>
                           +91 97999 46027
                           </a>
                        </li>
                        <li>
                           <a href="mailto:rightuni1@gmail.com">
                           <i class="fas fa-envelope"></i>
                           rightuni1@gmail.com
                           </a>
                        </li>
                        <li>
                           <a href="https://www.google.com/maps/search/2nd+floor,+Dev+Tower,+adjoining+building+to+Anand+Motors,+Opp.+main+Kumbha+Marg+Gate,+Pratap+Nagar,+Jaipur/@26.79636,75.8153173,16.75z?entry=ttu">
                           <i class="fas fa-map-marker-alt"></i>
                           2nd floor, Dev Tower, adjoining building to Anand Motors, Opp. main Kumbha Marg Gate, Pratap Nagar, Jaipur
                           </a>
                        </li>
                     </ul>
                  </div>
               </div>
               <!-- item -->
            </div>
         </div>
      </footer>
      <!-- Footer End -->
      <!-- Copyright Start -->
      <div class="thm-bg-color-one">
         <div class="container">
            <div class="row">
               <div class="col-12 text-center">
                  <div class="copyright">
                     <p class="mb-0">Copyright <a href="http://68.183.80.139/" class="text-white fw-500">www.rightuni.in</a> <span id="year">2022</span>. All Right Reserved</p>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Copyright End -->
      <a href="#" data-target="html" class="back-to-top ft-sticky">
      <i class="fal fa-chevron-up"></i>
      </a>
      <!-- Scripts -->
      <script src="assets/js/plugins/jquery-3.6.0.min.js"></script>
      <script src="assets/js/plugins/bootstrap.bundle.min.js"></script>
      <script src="assets/js/plugins/slick.min.js"></script>
      <script src="assets/js/plugins/jquery.nice-select.js"></script>
      <script src="assets/js/plugins/sweetalert.min.js"></script>
      <script src="assets/js/custom.js"></script>



   </body>
</html>
