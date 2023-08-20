document.addEventListener("DOMContentLoaded", function() {
  // Permet l'ouverture et fermeture du menu de navigation
  function openNav() {
    // Définition des variables
    const open = document.querySelector("#bars");
    const close = document.querySelector(".uil-times");
    const nav = document.querySelector("nav");
    const overlay = document.querySelector(".overlay");
    const body = document.body; // Sélectionnez le corps

    // Fonction pour désactiver/réactiver le défilement
    function toggleScroll() {
      if (body.style.overflow === "hidden") {
        body.style.overflow = ""; // Réactiver le défilement
      } else {
        body.style.overflow = "hidden"; // Désactiver le défilement
      }
    }

    // Ouverture du menu de navigation en cliquant sur le menu
    open.addEventListener("click", () => {
      nav.classList.add("active-nav");
      overlay.classList.add("active-overlay");
      toggleScroll(); // Désactiver le défilement
    });

    // Fermeture du menu de navigation en cliquant sur l'overlay ou la croix
    function closeNav() {
      nav.classList.remove("active-nav");
      overlay.classList.remove("active-overlay");
      toggleScroll(); // Réactiver le défilement
    }

    overlay.addEventListener("click", closeNav);
    close.addEventListener("click", closeNav);
  }

  // Appel de la fonction
  openNav();
});


// Ouvre un sous-menu du menu principal
document.addEventListener('DOMContentLoaded', function() {
  const subButton = document.querySelector('.sub-button');
  const subMenu = subButton.nextElementSibling;
  const angleDown = document.querySelector('#angle-down');

  subButton.addEventListener('click', function() {
      const computedStyle = window.getComputedStyle(subMenu);
      const currentMaxHeight = computedStyle.getPropertyValue('max-height');

      if (currentMaxHeight === '0px') {
          subMenu.style.maxHeight = subMenu.scrollHeight + 'px';
          angleDown.style.transform = 'rotate(180deg)';
      } else {
          subMenu.style.maxHeight = '0px';
          angleDown.style.transform = 'rotate(0deg)';
      }
  });
});

// Ajouter un champ de casting
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