<?php 
$uriSegments = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$showViewAll = !in_array('universities', $uriSegments);

?>
<section class="section-padding">
   <div class="container">
      <div class="section-header">
         <h3 class="title">Popular <span>Universities</span></h3>
         <p class="text">Explore the profiles of these top universities and find the one that best fits your educational goals.</p>
      </div>
      <div class="row justify-content-center">
         <!-- Course Name Tab starts -->
         <div class="wrapper">
            <div class="iconn icon"><i id="left" class="bi bi-chevron-left"></i></div>
            <ul class="tabs-box">
               <?php
                  $sql = "SELECT id, name, slug, status FROM courses WHERE status = 1 AND deleted = 0";
                  $result = $conn->query($sql);

                  while($course_data = mysqli_fetch_assoc($result)) { ?> 
                     <li class="tab" data-cat-id="<?= $course_data['id']; ?>" onclick="fetchColleges('<?= $course_data['id']; ?>')"><?= $course_data['name']; ?></li>
               <?php } ?>
            </ul>
            <div class="iconn"><i id="right" class="bi bi-chevron-right"></i></div>
         </div>
         <!-- Course Name Tab ends --> 

         <!-- Items start -->
         <div class="row" id="college-list">
            <!-- Colleges will be loaded here via AJAX -->
         </div>
         <!-- Items end -->
         <div id="view-all-container" class="text-center" style="display: none;">
            <a href="universities">
               <h4 class="getallcollege">View All Colleges</h4>
            </a>
         </div>
      </div>
   </div>
</section>

<script>

function truncateText(text, wordLimit) {
   const words = text.split(' ');
   if (words.length > wordLimit) {
       return words.slice(0, wordLimit).join(' ') + '...';
   }
   return text;
}


function fetchColleges(id) {
   $.ajax({
      url: "get_colleges.php",
      method: "GET",
      data: { id: id },
      dataType: 'json',
      success: function(response) {
         var collegeList = $('#college-list');
         collegeList.empty();
         var result = response.colleges;
         var totalCount = response.totalCount;

         if (result && result.length > 0) {
            result.forEach(function(college) {
               // Parse the date
               var date = new Date(college.created_at);
               var formattedDate = date.getFullYear() + '-' + 
               ('0' + (date.getMonth() + 1)).slice(-2) + '-' + 
               ('0' + date.getDate()).slice(-2);
               var collegeItem = `
                  <div class="col-lg-4 col-md-6 fetchColleges category-${college.id}">
                     <div class="listing_box">
                        <div class="listing_image">
                           <a href="college/${college.slug}" class="d-flex h-100">
                              <img src="admin/images/${college.image}" alt="Rightuni" class="image-fit">
                           </a>
                        </div>
                        <div class="listing_caption">
                           <h4 class="title"><a href="college/${college.slug}">${truncateText(college.name, 2)}</a></h4>
                           <ul class="listing_meta">
                              <li><i class="fas fa-map-marker-alt"></i> ${college.city}, ${college.state_name}</li>
                              <li><i class="fas fa-clock"></i> ${formattedDate}</li>
                           </ul>
                        </div>
                        <div class="listing_footer">
                              <a class="listing_btn btn" href="college/${college.slug}">Read More..</a>
                           
                        </div>
                     </div>
                  </div>
               `;
               collegeList.append(collegeItem);
            });

            // Show "View All Colleges" button if totalCount is more than 9
            if (totalCount > 9) {
               $('#view-all-container').show();
            } else {
               $('#view-all-container').hide();
            }
         } else {
            collegeList.html("<p class='text-center bg-danger text-white' style='width: 20%; margin: auto'>Data Not Found</p>");
            $('#view-all-container').hide();
         }
      },
      error: function(jqXHR, textStatus, errorThrown) {
         console.log(textStatus, errorThrown);
         $('#college-list').html("<p class='text-center bg-danger text-white' style='width: 20%; margin: auto'>Error fetching data</p>");
         $('#view-all-container').hide();
      }
   });
}

document.addEventListener('DOMContentLoaded', function() {
   fetchColleges(1);
});

document.addEventListener('DOMContentLoaded', () => {
   const tabsBox = document.querySelector(".tabs-box"),
      allTabs = tabsBox.querySelectorAll(".tab"),
      arrowIcons = document.querySelectorAll(".iconn i");

   let isDragging = false;

   const handleIcons = () => {
      const maxScrollableWidth = tabsBox.scrollWidth - tabsBox.clientWidth;
      arrowIcons[0].parentElement.style.display = tabsBox.scrollLeft <= 0 ? "none" : "flex";
      arrowIcons[1].parentElement.style.display = maxScrollableWidth - tabsBox.scrollLeft <= 1 ? "none" : "flex";
   };

   arrowIcons.forEach(iconn => {
      iconn.addEventListener("click", () => {
         const scrollAmount = iconn.id === "left" ? -100 : 100;
         tabsBox.scrollLeft += scrollAmount;
         handleIcons();
      });
   });

   allTabs.forEach(tab => {
      tab.addEventListener("click", () => {
         tabsBox.querySelector(".active")?.classList.remove("active");
         tab.classList.add("active");

         // Center the clicked tab
         const tabRect = tab.getBoundingClientRect();
         const boxRect = tabsBox.getBoundingClientRect();
         const offset = tabRect.left - boxRect.left - (boxRect.width / 2) + (tabRect.width / 2);
         tabsBox.scrollLeft += offset;

         handleIcons();
      });
   });

   const dragging = (e) => {
      if (!isDragging) return;
      tabsBox.classList.add("dragging");
      tabsBox.scrollLeft -= e.movementX;
      handleIcons();
   };

   const dragStop = () => {
      isDragging = false;
      tabsBox.classList.remove("dragging");
   };

   tabsBox.addEventListener("mousedown", () => isDragging = true);
   tabsBox.addEventListener("mousemove", dragging);
   document.addEventListener("mouseup", dragStop);

   handleIcons(); // Initial call to set the arrow icons correctly

   // Set the default active tab with cat_id 1
   const defaultTab = tabsBox.querySelector(".tab[data-cat-id='1']");
   if (defaultTab) {
      defaultTab.classList.add("active");

      // Center the default active tab
      const tabRect = defaultTab.getBoundingClientRect();
      const boxRect = tabsBox.getBoundingClientRect();
      const offset = tabRect.left - boxRect.left - (boxRect.width / 2) + (tabRect.width / 2);
      tabsBox.scrollLeft += offset;

      handleIcons();
   }
});
</script>