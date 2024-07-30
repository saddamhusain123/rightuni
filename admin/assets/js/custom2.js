
document.addEventListener('DOMContentLoaded', () => {
  const tabsBox = document.querySelector(".tabs-box"),
    allTabs = tabsBox.querySelectorAll(".tab"),
    arrowIcons = document.querySelectorAll(".icon i");

  let isDragging = false;

  const handleIcons = () => {
    const maxScrollableWidth = tabsBox.scrollWidth - tabsBox.clientWidth;
    arrowIcons[0].parentElement.style.display =
      tabsBox.scrollLeft <= 0 ? "none" : "flex";
    arrowIcons[1].parentElement.style.display =
      maxScrollableWidth - tabsBox.scrollLeft <= 1 ? "none" : "flex";
  };

  arrowIcons.forEach((icon) => {
    icon.addEventListener("click", () => {
      const scrollAmount = icon.id === "left" ? -100 : 100;
      tabsBox.scrollLeft += scrollAmount;
      handleIcons();
    });
  });

  allTabs.forEach((tab) => {
    tab.addEventListener("click", () => {
      tabsBox.querySelector(".active")?.classList.remove("active");
      tab.classList.add("active");

      // Center the clicked tab
      const tabRect = tab.getBoundingClientRect();
      const boxRect = tabsBox.getBoundingClientRect();
      const offset =
        tabRect.left - boxRect.left - boxRect.width / 2 + tabRect.width / 2;
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

  tabsBox.addEventListener("mousedown", () => (isDragging = true));
  tabsBox.addEventListener("mousemove", dragging);
  document.addEventListener("mouseup", dragStop);

  handleIcons(); 
  
});






