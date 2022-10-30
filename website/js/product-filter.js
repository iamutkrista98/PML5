const filterList = document.querySelector('.products-filter');

document.querySelector(".products-filter-mobile-icon").onclick = () => {
  filterList.classList.add("show-filter");
}

document.querySelector(".close-filter").onclick = () => {
  filterList.classList.remove("show-filter");
}

//For Filter Ends