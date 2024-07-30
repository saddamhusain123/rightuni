const tabs = document.querySelectorAll(".scrollable-tabs-container button");
const rightArrow = document.querySelector(
    ".scrollable-tabs-container .right-arrow i"
);
const tabsList = document.querySelector(".scrollable-tabs-container ul");
const leftArrowContainer = document.querySelector(".scrollable-tabs-container .left-arrow");
const rightArrowContainer = document.querySelector(".scrollable-tabs-container .right-arrow");

const removeAllActiveClasses = () => {
    tabs.forEach(tab => {
        tab.classList.remove("active");
    });
};

tabs.forEach(tab =>{
    tab.addEventListener("click", () =>{
        removeAllActiveClasses
        tab.classList.add("active");
    });
});

const manageIcons = () => {
    if (tabsList.scrollLeft >= 20){
        leftArrowContainer.classList.add("active");
    } else {
        leftArrowContainer.classList.remove("active");
    }
}

rightArrow.addEventListener("click", () => {
    tabsList.scrollLeft += 200;
    manageIcons();
});