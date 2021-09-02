class Toggle {
  modals(area) {
    // area = all modals "buttons" container
    document.querySelector(area).addEventListener("click", function (e) {
      let modalId = document.getElementById(`${e.target.id}s`); // target modal and it's content
      modalId.style.display = "block";

      // close icon element <i> " X " when modals is on
      document
        .querySelector(`#${e.target.id}s > div > i`)
        .addEventListener("click", function () {
          modalId.style.display = "none";
        });
    });
  }

  fields(area) {
    // area you want the function to find/use "button" with id="toggle-SOMETHING"
    if (document.querySelector(area)) {
      document.querySelector(area).addEventListener("click", function (e) {
        if (/toggle/.test(e.target.id)) {
          document
            .getElementById(e.target.id)
            .parentElement.classList.toggle("hidden-field");
        }
      });
    }
  }
}

export default Toggle;
