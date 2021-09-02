import Verify from "./Verify.js";

class HandleForm extends Verify {
  constructor(
    REGEX_NUMBER,
    REGEX_PRICE,
    REGEX_ADDITIONNAL,
    REGEX_MAIL,
    REGEX_TYPE,
    REGEX_SOCIETY,
    REGEX_ADDRESS,
    REGEX_POSTAL,
    REGEX_MATRICULE,
    REGEX_TEXT,
    REGEX_PHONE,
    REGEX_DATE,
    REGEX_PASSWORD_1,
    REGEX_PASSWORD_2,
    REGEX_PASSWORD_3,
    REGEX_PASSWORD_4
  ) {
    super(
      REGEX_NUMBER,
      REGEX_PRICE,
      REGEX_ADDITIONNAL,
      REGEX_MAIL,
      REGEX_TYPE,
      REGEX_SOCIETY,
      REGEX_ADDRESS,
      REGEX_POSTAL,
      REGEX_MATRICULE,
      REGEX_TEXT,
      REGEX_PHONE,
      REGEX_DATE,
      REGEX_PASSWORD_1,
      REGEX_PASSWORD_2,
      REGEX_PASSWORD_3,
      REGEX_PASSWORD_4
    );

    this.SUBMIT = document.getElementById("submit"); // demand.phtml
    this.SUBMIT_NEW = document.getElementById("submit-new"); // order.phtml & sanction.phtml

    this.beginDate;
    this.endDate;
    this.date1;
    this.date2;
    this.mail;
    this.password;
    this.verifyPassword;
    this.matricule;
    this.phone;
    this.list;

    this.agent = document.getElementById("id-agent");
    this.agents = document.querySelectorAll("#id-agent > option");
    this.agentsList = [];
    this.client = document.getElementById("id-client");
    this.clients = document.querySelectorAll("#id-client > option");
    this.clientsList = [];
  }

  // == Handle subscribe.phtml & connexion.phtml <form>
  handleSubscribeConnexion(submitButton, statut) {
    if (submitButton) {
      submitButton.addEventListener("click", (e) => {
        if (document.getElementById(`mail-${statut}`)) {
          this.verifRequiredData(`mail-${statut}`, this.REGEX_MAIL, 5, 80);
        }
        this.verifPassword(`pass-${statut}`);
        if (document.getElementById(`phone-${statut}`)) {
          this.verifText(`#${statut} .field`);
          this.verifPhone(`phone-${statut}`);
          if (this.verifPassword(`pass-${statut}`)) {
            this.reVerifPassword(`pass-verif-${statut}`);
          }
        }
        if (this.status.containErrors()) {
          e.preventDefault();
        }
        this.status.resetErrors();
      });
    }
  }

  // == Handle demand.phtml & order.phtml <form>
  validForm() {
    if (this.SUBMIT) {
      this.SUBMIT.addEventListener("click", (e) => {
        this.status.resetErrors();
        this.verifObligatory(".obligatory ~ .field >"); // target every inputs[number/text] in div .field brother of legend .obligatory
        this.verifOptionnalData("additionnal", this.REGEX_ADDITIONNAL, 0, 1000);
        this.verifOptionnalNumber(); // verif all optionnals input[type="number"]
        if (this.status.containErrors()) {
          e.preventDefault();
        }
      });
    }
  }

  // == Handle sanction.phtml & order.phtml
  new() {
    if (this.SUBMIT_NEW) {
      // Get all agents ID from of select name="id-agent" via php at the loading of the page (for sanction.phtml)
      if (this.agent) {
        this.agents.forEach((e) => {
          this.agentsList.push(e.value);
        });
      }

      // Get all clients ID from of select name="id-client" via php at the loading of the page (for order.phtml)
      if (this.client) {
        this.clients.forEach((e) => {
          this.clientsList.push(e.value);
        });
      }

      this.SUBMIT_NEW.addEventListener("click", (e) => {
        this.status.resetErrors();
        this.verifNumber(".obligatory ~ .field >");
        this.verifOptionnalNumber();
        this.verifRequiredData("priceTTC", this.REGEX_PRICE, 3, 9000);
        this.verifDate("begin-date", "end-date");
        if (this.agent) {
          this.verifEntitled(); // Verif select name="id-sanction"
          this.verifText(".obligatory ~ .field >");
          this.verifAgent(this.agent, this.agentsList);
        }
        if (this.client) {
          this.verifClient(this.client, this.clientsList);
        }
        if (this.status.containErrors()) {
          e.preventDefault();
        }
      });
    }
  }

  // == Handle account.phtml & modify.phtml
  edit(statut) {
    let submit = document.getElementById("submit-edit-" + statut);
    if (submit) {
      submit.addEventListener("click", (e) => {
        this.status.resetErrors();

        // Infos required for subscription are required in edition (so user cannot erase it)
        this.verifPhone(`phone-${statut}`);
        this.verifRequiredData(`mail-${statut}`, this.REGEX_MAIL, 2, 50);
        this.verifRequiredData(`last-name-${statut}`, this.REGEX_TEXT, 1, 40);
        this.verifRequiredData(`first-name-${statut}`, this.REGEX_TEXT, 1, 40);

        // Handle Optionnals address, postal, city, country, grade, matricule, site-name & Required last-name & first-name
        this.verifText(".optionnal ~ .field >");

        // Edit password is optionnal
        let password = document.getElementById(`pass-${statut}`);
        let rePassword = document.getElementById(`pass-verif-${statut}`);
        if (
          this.REGEX_PASSWORD_1.test(password.value) &&
          this.REGEX_PASSWORD_2.test(password.value) &&
          this.REGEX_PASSWORD_3.test(password.value) &&
          this.REGEX_PASSWORD_4.test(password.value) &&
          password.value === rePassword.value &&
          rePassword.value != ""
        ) {
          this.status.success(rePassword);
        } else if (rePassword.value === "" && password.value === "") {
          this.status.normal(rePassword);
        } else if (password.value !== rePassword.value) {
          this.status.error(
            rePassword,
            "Les mots de passe ne correspondent pas"
          );
        } else {
          this.status.error(
            password,
            "Le mot de passe doit contenir au moins 6 caractères dont 1 chiffre, 1 lettre minuscule et 1 majuscule, 1 caractère special(.#~+=*-_+²$=¤)"
          );
        }

        // Optionnals client
        if (statut === "client") {
          this.verifOptionnalData("superficie", this.REGEX_NUMBER, 0, 20);
          this.verifOptionnalData("type", this.REGEX_TYPE, 3, 20);
        }

        if (this.status.containErrors()) {
          e.preventDefault();
        }
      });
    }
  }
}

export default HandleForm;
