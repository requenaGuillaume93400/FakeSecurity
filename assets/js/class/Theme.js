class Theme {
  constructor() {
    // Create change theme button in js, so if user block js, button is not accessible
    this.button = document.createElement("button");
    this.button.setAttribute("id", "change-theme-btn");
    this.button.classList.add("change-btn");
    this.button.textContent = "Changez de thème";
    document
      .querySelector("header")
      .insertAdjacentElement("afterend", this.button);
  }

  // Active or desactive dark-theme
  changeTheme() {
    if (this.button) {
      this.button.addEventListener("click", function () {
        let darkThemeEnabled = document.body.classList.toggle("dark-theme");
        localStorage.setItem("dark-theme-enabled", darkThemeEnabled);
      });

      if (JSON.parse(localStorage.getItem("dark-theme-enabled"))) {
        document.body.classList.add("dark-theme");
      }
    }
  }
}

export default Theme;
