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