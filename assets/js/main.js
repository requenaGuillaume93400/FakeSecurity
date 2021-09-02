"use strict";
import Theme from "./class/Theme.js";
import Api from "./class/Api.js";
import Toggle from "./class/Toggle.js";
import Price from "./class/Price.js";
import HandleForm from "./class/HandleForm.js";
import AjaxRequest from "./class/AjaxRequest.js";

/*************************************************************************************************
 ******************************************** CONST **********************************************
 *************************************************************************************************/
const SUBMIT_CLIENT = document.getElementById("submit-client"); // Inscription & Connexion
const SUBMIT_AGENT = document.getElementById("submit-agent"); // Inscription & Connexion
const theme = new Theme();
const toggle = new Toggle();
const price = new Price();
const handleForm = new HandleForm();
const api = new Api();
const ajaxRequest = new AjaxRequest();

/*************************************************************************************************
 ****************************************** MAIN CODE ********************************************
 *************************************************************************************************/
document.addEventListener("DOMContentLoaded", function () {
  theme.changeTheme(); // All pages

  toggle.modals("footer > div"); // footer, all pages
  toggle.fields("main > form"); // demand.phtml
  toggle.fields(".area"); // backOffice.phtml - order.phtml - sanction.phtml

  handleForm.handleSubscribeConnexion(SUBMIT_CLIENT, "client"); // connexion.phtml - subscribe.phtml
  handleForm.handleSubscribeConnexion(SUBMIT_AGENT, "agent"); // connexion.phtml - subscribe.phtml
  handleForm.validForm(); // demand.phtml
  handleForm.new(); // new order.phtml - sanction.phtml
  handleForm.edit("agent"); // account.phtml - modify.phtml
  handleForm.edit("client"); // account.phtml - modify.phtml

  price.showEstimationPrice(); // demand.phtml

  api.run(); // bonus.phtml

  ajaxRequest.handleOperation(); // backoffice.phtml
  ajaxRequest.handleEdit(); // account.phtml - modify.phtml
});
