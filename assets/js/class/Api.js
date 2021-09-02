class Api {
  constructor() {
    this.closeBtn = document.getElementById("close-btn");
    this.quote = document.getElementById("quote");
    this.location = document.getElementById("loc");
  }

  run() {
    if (this.closeBtn) {
      // Launch api script (if we are on page bonus)
      this.genQuote(this.quote);

      // If user click to delete the info message, local storage will remeber it, message won't be display again
      this.closeBtn.addEventListener("click", function () {
        localStorage.setItem("desactive-bonus-message", true);
        document.querySelector(".api article").style.display = "none";
      });

      // If user didn't clicked to delete message (or if local storage has been cleaned), display message
      if (!JSON.parse(localStorage.getItem("desactive-bonus-message"))) {
        document.querySelector(".api article").style.display = "initial";
      }
    }

    // Get Location for fail message (if user tried to access another member's account)
    this.getLocation();
  }

  // Launch double api script for bonus.phtml
  genQuote(element) {
    if (element) {
      function getQuote() {
        // https://api.quotable.io/random  -> github -> lukePeavy
        fetch("https://api.quotable.io/random")
          .then((response) => response.json())
          .then((data) => {
            element.innerHTML = `<q>${data.content}</q> ${data.author}`;
          });

        fetch("https://picsum.photos/1600/1000").then((response) => {
          document.getElementById(
            "pic"
          ).innerHTML = `<img src=${response.url}>`;
        });
      }

      // If we click on the quote, we get a new quote & img
      element.addEventListener("click", getQuote);

      // In every case we load a quote & img when page load
      getQuote();
    }
  }

  // Launch double api for geolocation connexion.phtml
  async getLocation() {
    if (this.location) {
      // Get user ip address
      const ip = await fetch("https://api.ipify.org?format=json")
        .then((result) => result.json())
        .then((json) => json.ip);

      // Get user city address
      const city = await fetch("https://freegeoip.app/json/")
        .then((result) => result.json())
        .then((json) => json.city);

      this.location.textContent = `près de ${city} via votre ip ${ip}`;
    }
  }
}

export default Api;
