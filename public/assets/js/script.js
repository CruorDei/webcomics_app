// JavaScript nav v1
const button = document.getElementById("toggle-button");
const element = document.getElementById("toggle-element");

button.addEventListener("click", function() {
  if (element.classList.contains("active")) {
    // Si l'élément est actif, on le masque et on enlève la classe "active"
    element.style.display = "none";
    element.classList.remove("active");
  } else {
    // Sinon, on affiche l'élément et on ajoute la classe "active"
    element.style.display = "flex";
    element.classList.add("active");
  }
});

//pagination accueil

let currentPage = 1;

document.getElementById('show-more').addEventListener('click', function () {
currentPage++;
if (currentPage > numPages) {
document.getElementById('show-more').style.display = 'none';
return;
}
let nextPage = document.getElementById('page' + currentPage);
nextPage.style.display = 'grid';
});

//cat

// const cat = document.querySelector(".cat");
// const catChildren = document.querySelectorAll(".cat-child");

// catChildren.forEach((catChild) => {
//   cat.appendChild(catChild);
// });
