document.addEventListener("DOMContentLoaded", function() {

  // Permet l'ouverture et fermeture du menu de navigation
  function openNav() {

    const open = document.querySelector("#bars");
    const close = document.querySelector(".uil-times");
    const nav = document.querySelector("nav");
    const overlay = document.querySelector(".overlay");
    
    open.addEventListener("click", () => {
      nav.classList.add("active-nav");
      overlay.classList.add("active-overlay");
    });
    close.addEventListener("click", () => {
      nav.classList.remove("active-nav");
      overlay.classList.remove("active-overlay");
    });
  }
  openNav();
});