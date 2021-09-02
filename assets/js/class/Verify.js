import Status from "./Status.js";

class Verify {
  constructor(status = new Status()) {
    this.status = status;

    this.REGEX_NUMBER = /[0-9]{1,}/;
    this.REGEX_PRICE = /[0-9]+[\.,]{1}[0-9]{2}/;
    this.REGEX_ADDITIONNAL = /^[^<>]+$/;
    this.REGEX_MAIL =
      /^[a-zA-Z0-9.!#$%&'*+=?^_`{|}~-]+@{1}[a-zA-Z0-9]+[-]{0,}[a-zA-Z0-9]+\.{1}[a-zA-Z]{2,}/;
    this.REGEX_TYPE =
      /alimentaire|bricolage|culturel|bijouterie|gare|aeroport|concert|sport|autre/;
    this.REGEX_SOCIETY =
      /^[a-zA-z0-9&'\-éÉèÈàÀäÄÂËçÇêîÎÏïÊÔÛÖÜüôöûù]{1,}[a-zA-z0-9&'\-éÉèÈàÀäÄÂËçÇêîÎÏïÊÔÛÖÜüôöûù ]*[a-zA-z0-9&'\-éÉèÈàÀäÄÂËçÇêîÎÏïÊÔÛÖÜüôöûù]{1,}$/;
    this.REGEX_ADDRESS =
      /^[a-zA-Z0-9'éÉèÈàÀäÄÂËçÇêîÎÏïÊÔÛÖÜüôöûù]{1,}[a-zA-Z0-9'\-éÉèÈàÀäÄÂËçÇêîÎÏïÊÔÛÖÜüôöûù ]*[a-zA-Z0-9'éÉèÈàÀäÄÂËçÇêîÎÏïÊÔÛÖÜüôöûù]{1,}$/;
    this.REGEX_POSTAL = /[A-Z0-9]{5}/;
    this.REGEX_MATRICULE = /[A-Z]{4}[0-9]{1,4}[0-9A-Z]{4}$/;
    this.REGEX_TEXT =
      /^[a-zA-z'éÉèÈàÀäÄÂËçÇêîÎÏïÊÔÛÖÜüôöûù]{1,}[a-zA-z'éÉèÈàÀäÄÂËçÇêîÎÏïÊÔÛÖÜüôöûù\- ]*[a-zA-z'éÉèÈàÀäÄÂËçÇêîÎÏïÊÔÛÖÜüôöûù]{1,}$/;
    this.REGEX_PHONE = /^[+0-9]{0,}[0-9]$/;
    this.REGEX_DATE = /[0-9]{4}-{1}[0-9]{2}-{1}[0-9]{2}/;
    this.REGEX_PASSWORD_1 = /[a-z]{1}/;
    this.REGEX_PASSWORD_2 = /[A-Z]{1}/;
    this.REGEX_PASSWORD_3 = /[0-9]{1}/;
    this.REGEX_PASSWORD_4 = /[.#~+=*-_+²$=¤]{1}/;
  }

  // Verify Data field by giving input-id, regex, min-length and max length, if data is incorrect throw an error message
  verifData(id, regex, minLength, maxLength) {
    let input = document.getElementById(id);
    if (this.dataLength(input, minLength, maxLength)) {
      if (regex.test(input.value)) {
        this.status.success(input);
      } else {
        this.status.error(input, "Le champ contient des caractères interdits.");
      }
    } else {
      this.status.error(
        input,
        `Le champ doit contenir entre ${minLength + 1} et ${
          maxLength - 1
        } caractères.`
      );
    }
  }

  // Verify if Data matches in a range of min-length and max-length
  dataLength(input, minLength, maxLength) {
    if (input.value.length > minLength && input.value.length < maxLength) {
      return true;
    }
    return false;
  }

  // Verify Optionnals Data
  verifOptionnalData(id, regex, minLength, maxLength) {
    let input = document.getElementById(id);
    if (input) {
      if (input.value != "") {
        this.verifData(id, regex, minLength, maxLength);
      } else {
        this.status.normal(input);
      }
    }
  }

  // Verify Required Data
  verifRequiredData(id, regex, minLength, maxLength) {
    let input = document.getElementById(id);
    if (input && input.value != "") {
      this.verifData(id, regex, minLength, maxLength);
    } else {
      this.status.error(input, "Le champ doit être complété.");
    }
  }

  // Verify all Required fieldset in demand.phtml & order.phtml
  verifObligatory(selector) {
    this.verifNumber(selector);
    this.verifText(selector);
    this.verifRequiredData("mail", this.REGEX_MAIL, 5, 80);
    this.verifPhone("phone");
    this.verifRequiredData("type", this.REGEX_TYPE, 3, 13);
    this.verifDate("begin-date", "end-date");
  }

  // Verify format of Password (we need a strong password !)
  verifPassword(id) {
    this.password = document.getElementById(id);
    if (
      this.REGEX_PASSWORD_1.test(this.password.value) &&
      this.REGEX_PASSWORD_2.test(this.password.value) &&
      this.REGEX_PASSWORD_3.test(this.password.value) &&
      this.REGEX_PASSWORD_4.test(this.password.value)
    ) {
      if (this.password.value.length > 6) {
        this.status.success(this.password);
        return true;
      } else {
        this.status.error(
          this.password,
          "Doit contenir au moins 6 caractères."
        );
      }
    } else {
      this.status.error(
        this.password,
        "Doit contenir au moins 6 caractères et au moins : 1 minuscule, 1 majuscule, 1 chiffre et 1 caractère spécial (.#~+=*-_+²$=¤)."
      );
    }
  }

  // Verify is confirmation password matches with password
  reVerifPassword(id) {
    this.verifyPassword = document.getElementById(id);
    if (this.verifyPassword.value === this.password.value) {
      this.status.success(this.verifyPassword);
      return true;
    } else {
      this.status.error(
        this.verifyPassword,
        "Les mots de passe ne correspondent pas."
      );
    }
  }

  // Verify all input type="text" by value of input name=""
  verifText(selector) {
    let inputsText = document.querySelectorAll(
      selector + " input[type='text']"
    );
    inputsText.forEach((e) => {
      switch (e.name) {
        case "society":
          this.verifOptionnalData(e.id, this.REGEX_SOCIETY, 1, 50);
          break;

        case "address":
          this.verifOptionnalData(e.id, this.REGEX_ADDRESS, 0, 255);
          break;

        case "postal":
          this.verifOptionnalData(e.id, this.REGEX_POSTAL, 4, 6);
          break;

        case "matricule":
          this.verifOptionnalData(e.id, this.REGEX_MATRICULE, 8, 13);
          break;

        case "grade":
          this.verifOptionnalData(e.id, this.REGEX_TEXT, 1, 25);
          break;

        case "city":
        case "country":
        case "site-name":
          this.verifOptionnalData(e.id, this.REGEX_TEXT, 2, 40);
          break;

        // case last-name & first-name (in demand.phtml & subscribe.phtml) & reason (in sanction.phtml)
        default:
          this.verifRequiredData(e.id, this.REGEX_TEXT, 2, 60);
      }
    });
  }

  // Verify Phone data format
  verifPhone(id) {
    this.phone = document.getElementById(id);

    if (this.phone.value != "") {
      if (this.REGEX_PHONE.test(this.phone.value)) {
        if (this.phone.value.length === 10) {
          this.status.success(this.phone);
        }
        // Or with prefix format type " +33 "
        else if (this.phone.value.length === 12) {
          this.status.success(this.phone);
        } else {
          this.status.error(
            this.phone,
            "Le champ doit contenir 10 ou 12 caractères (chiffres et éventuellement + indicatif), sans espacements."
          );
        }
      } else {
        this.status.error(
          this.phone,
          "Le champ contient des caractères interdits."
        );
      }
    } else {
      this.status.error(this.phone, "Le champ doit être complété.");
    }
  }

  // Verify if Entitled's select value is ok (if not user modified the html) used in new(), sanction.phtml
  verifEntitled() {
    let entitled = document.getElementById("id-sanction");
    if (entitled.value != "") {
      if (entitled.value > 0 && entitled.value < 8) {
        this.status.success(entitled);
      } else {
        this.status.error(
          entitled,
          "La sanction indiquée n'existe pas. Arretez de jouer avec le html, ou bagarre !"
        );
      }
    } else {
      this.status.error(entitled, "Ce champ est obligatoire.");
    }
  }

  // Verify if Agent's select value is ok (if not user modified the html), used in new(), sanction.phtml
  verifAgent(agent, agentsList) {
    if (agent.value != "") {
      // If value is a number & is includes in agentsList, then user exist
      if (!isNaN(agent.value) && agentsList.includes(agent.value)) {
        this.status.success(agent);
      } else {
        this.status.error(agent, "Cet agent n'existe pas !");
      }
    } else {
      this.status.error(agent, "Ce champ est obligatoire.");
    }
  }

  // Verify if Client's select value is ok (if not user modified the html), used in new(), order.phtml
  verifClient(client, clientsList) {
    if (client.value != "") {
      // If value is a number & is includes in clientsList[], then user exist. But if user hasn't subscribed he has no id, then i put 0 by default
      if (
        (!isNaN(client.value) && clientsList.includes(client.value)) ||
        client.value === "0"
      ) {
        this.status.success(client);
      } else {
        this.status.error(client, "Cet client n'existe pas !");
      }
    } else {
      this.status.error(client, "Ce champ est obligatoire.");
    }
  }

  // Verify if dates Data are coherent
  verifDate(firstDateInput, secondDateInput) {
    this.date1 = document.getElementById(firstDateInput);
    this.date2 = document.getElementById(secondDateInput);
    if (
      this.REGEX_DATE.test(this.date1.value) &&
      this.date1.value < this.date2.value &&
      this.REGEX_DATE.test(this.date2.value) &&
      this.date2.value > this.date1.value
    ) {
      this.status.success(this.date1);
      this.status.success(this.date2);
    } else {
      this.status.error(
        this.date1,
        "L'une des dates ou les deux semblent incorrect."
      );
      this.status.error(
        this.date2,
        "L'une des dates ou les deux semblent incorrect."
      );
    }
  }

  // Verify number of input type="number" that are integer (price isn't integer)
  verifNumber(selector) {
    let inputsNumber = document.querySelectorAll(
      `${selector} input[type='number']`
    );
    inputsNumber.forEach((e) => {
      // If id contain "price" do nothing (i need a decimal number in order.phtml)
      if (!/price/.test(e.id)) {
        this.checkThisNumber(e);
      }
    });
  }

  // Used in veriNumber to check if data is indeed an integer number
  checkThisNumber(e) {
    if (
      e.value > 0 &&
      e.value != "" &&
      !isNaN(e.value) &&
      // Decimals not accepted
      !/[\.]/.test(e.value)
    ) {
      this.status.success(e);
    } else {
      this.status.error(
        e,
        "Le champ doit contenir un nombre entier supérieur à 0."
      );
    }
  }

  // Verify inputs type="number" that aren't required to complete a form (demand.phtml and order.phtml)
  verifOptionnalNumber() {
    this.list = document.querySelectorAll(".optionnal");
    for (let i = 0; i < this.list.length; i++) {
      let inputsNumber = document.querySelectorAll(
        `#${this.list[i].id} ~ .field > input[type='number']`
      );

      // inputsNumber is a Node list containing all inputs type="number" in an optionnal fieldset
      switch (inputsNumber.length) {
        case 1:
          if (inputsNumber[0].value != "") {
            this.checkOptionnalNumber(inputsNumber);
          } else {
            this.status.normal(inputsNumber[0]);
          }
          break;
        case 2:
          if (inputsNumber[0].value != "" || inputsNumber[1].value != "") {
            this.checkOptionnalNumber(inputsNumber);
          } else if (
            inputsNumber[0].value == "" &&
            inputsNumber[1].value == ""
          ) {
            inputsNumber.forEach((item) => this.status.normal(item));
          }
          break;
        case 3:
          if (
            inputsNumber[0].value != "" ||
            inputsNumber[1].value != "" ||
            inputsNumber[2].value != ""
          ) {
            this.checkOptionnalNumber(inputsNumber);
          } else if (
            inputsNumber[0].value == "" &&
            inputsNumber[1].value == "" &&
            inputsNumber[2].value == ""
          ) {
            inputsNumber.forEach((item) => this.status.normal(item));
          }
          break;
      }
    }
  }

  // Verify optionnals inputs type="number"
  checkOptionnalNumber(inputsNumber) {
    inputsNumber.forEach((e) => {
      this.checkThisNumber(e);
    });
  }
}

export default Verify;
