class Status {
  constructor() {
    this.nextElement;
    this.errors = [];
  }

  error(element, message) {
    this.nextElement = element.parentElement.nextElementSibling;
    if (this.nextElement != null && this.nextElement.className === "error") {
      // Do nothing if an error message has already been displayed
    } else {
      // create <p class="error"> and put it in the DOM after field, with text content
      let errorMessage = document.createElement("p");
      errorMessage.classList.add("error");
      element.style.backgroundColor = "pink";
      errorMessage.textContent = message;
      element = element.parentElement;
      element.insertAdjacentElement("afterend", errorMessage);
    }
    this.errors.push("errors");
  }

  success(element) {
    this.nextElement = element.parentElement.nextElementSibling;

    // If an error message exist, delete it
    if (this.nextElement != null && this.nextElement.className === "error") {
      this.nextElement.remove();
    }

    element.style.backgroundColor = "lightgreen";
    this.errors.push("success");
  }

  normal(element) {
    this.nextElement = element.parentElement.nextElementSibling;

    if (this.nextElement != null && this.nextElement.className === "error") {
      this.nextElement.remove();
    }

    element.style.backgroundColor = "white";
  }

  containErrors() {
    if (this.errors.includes("errors")) {
      return true;
    }
  }

  resetErrors() {
    this.errors = [];
    return this.errors;
  }
}

export default Status;
