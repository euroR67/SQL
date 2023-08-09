document.addEventListener("DOMContentLoaded", function() {
  // Permet l'ouverture et fermeture du menu de navigation
  function openNav() {
    // Définition des variables
    const open = document.querySelector("#bars");
    const close = document.querySelector(".uil-times");
    const nav = document.querySelector("nav");
    const overlay = document.querySelector(".overlay");
    // Ouverture du menu de navigation en cliquant sur le menu
    open.addEventListener("click", () => {
      nav.classList.add("active-nav");
      overlay.classList.add("active-overlay");
    });
    // Fermeture du menu de navigation en cliquant sur l'overlay
    overlay.addEventListener("click", () => {
      nav.classList.remove("active-nav");
      overlay.classList.remove("active-overlay");
    });
    // Fermeture du menu de navigation en cliquant sur la croix
    close.addEventListener("click", () => {
      nav.classList.remove("active-nav");
      overlay.classList.remove("active-overlay");
    });
  }
  // Appel de la fonction
  openNav();
});

document.addEventListener("DOMContentLoaded", function() {
  function addCasting() {
    const addCast = document.querySelector(".addBtn");
    const castDiv = document.querySelector(".cast-div");

    addCast.addEventListener("click", () => {
      newCast = castDiv.querySelector(".casting").cloneNode(true);
      castDiv.appendChild(newCast);
    });
  }
  addCasting();
});