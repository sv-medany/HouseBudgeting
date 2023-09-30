window.onload = function () {
  // Prevent default action of links in login/sign up page to use it in switching between login/setup instead
  let links = document.querySelectorAll(".link");
  if (links != null) {
    links.forEach((btn) => {
      btn.addEventListener("click", (event) => {
        event.preventDefault();
      });
    });
  }

  // display login box
  let login = document.querySelectorAll(".login-btn");
  if (login != null) {
    login.forEach((btn) => {
      btn.addEventListener("click", () => {
        loginSetup();
      });
    });
  }

  // display sign up box
  let signup = document.querySelectorAll(".signup-btn");
  if (signup != null) {
    signup.forEach((btn) => {
      btn.addEventListener("click", () => {
        signupSetup();
      });
    });
  }

  // validate parameters in login
  let loginform = document.querySelector(".login-form");
  if (loginform != null) {
    loginform.addEventListener("submit", (evnt) => {
      let email = document.querySelector("input[name='email']").value;
      let password = document.querySelector("input[name='password']").value;

      if (email === "") {
        alert("Please, Enter your Email!");
        evnt.preventDefault();
      } else if (password === "") {
        alert("Please, Enter your Password!");
        evnt.preventDefault();
      } else if (!ValidateEmail(email)) {
        alert("Please, Enter a valid email!");
        evnt.preventDefault();
      }
    });
  }

  // validate parameters in sign up
  let signupform = document.querySelector(".signup-form");
  if (signupform != null) {
    signupform.addEventListener("submit", (evnt) => {
      let name = document.querySelector("input[name='name']").value;
      let email = document.querySelectorAll("input[name='email']")[1].value;
      let password = document.querySelectorAll("input[name='password']")[1]
        .value;
      let confpassword = document.querySelector(
        "input[name='confpassword']"
      ).value;

      if (name === "") {
        alert("Please, Enter your name!");
        evnt.preventDefault();
      } else if (email === "") {
        alert("Please, Enter your Email!");
        evnt.preventDefault();
      } else if (password === "") {
        alert("Please, Enter a Password!");
        evnt.preventDefault();
      } else if (!ValidateEmail(email)) {
        alert("Please, Enter a valid email!");
        evnt.preventDefault();
      } else if (confpassword === "") {
        alert("Please, Confirm the password!");
        evnt.preventDefault();
      } else if (confpassword !== password) {
        alert(
          "Password and Password Confirmation aren't matching. Please, Confirm the password correctly!"
        );
        evnt.preventDefault();
      }
    });
  }

  // validate parameters in add purchase form
  let addpurchaseform = document.querySelector(".add-form");
  if (addpurchaseform != null) {
    addpurchaseform.addEventListener("submit", (evnt) => {
      let item = document.querySelector("input[name='item']").value;
      let initprice = document.querySelector("input[name='price']").value;
      let price = Number(initprice);
      if (item === "" || initprice === "") {
        alert("Make sure you leave no empty fields!");
        evnt.preventDefault();
      } else if (isNaN(price)) {
        alert("The price should be a correct numeric value. Try again!");
        evnt.preventDefault();
      } else if (price < 0) {
        alert("Prices can't be of negative values. Try again!");
        evnt.preventDefault();
      }
    });
  }

  // validate parameters in settings purchase form
  let settingsform = document.querySelector(".settings-form");
  if (settingsform != null) {
    settingsform.addEventListener("submit", (evnt) => {
      let username = document.querySelector("input[name='username']").value;
      let email = document.querySelector("input[name='email']").value;
      let initbudget = Number(
        document.querySelector("input[name='initbudget']").value
      );
      let consumingalert = Number(
        document.querySelector("input[name='consumingalert']").value
      );
      let goal = Number(document.querySelector("input[name='goal']").value);

      if (
        consumingalert === "" ||
        initbudget === "" ||
        email === "" ||
        username === "" ||
        goal === ""
      ) {
        alert("Make sure you leave no empty fields!");
        evnt.preventDefault();
      } else if (isNaN(initbudget) || isNaN(consumingalert) || isNaN(goal)) {
        alert(
          "Initial Budget and Consumption Alert both must be numeric values. Try again!"
        );
        evnt.preventDefault();
      } else if (
        typeof consumingalert == "number" &&
        (consumingalert > 100 || consumingalert < 0)
      ) {
        alert("Consumption Alert can't exceed 100% or be negative. Try again!");
        evnt.preventDefault();
      } else if (
        typeof goal == "number" &&
        typeof initbudget == "number" &&
        initbudget < goal
      ) {
        alert(
          "Your saving goal can't be less than the initial budget. Try again!"
        );
        evnt.preventDefault();
      } else if (
        (typeof goal == "number" &&
          typeof initbudget == "number" &&
          initbudget < 0) ||
        goal < 0
      ) {
        alert("Prices can't be of negative values. Try again!");
        evnt.preventDefault();
      }

      // NB: empty date is set by default to current date in php
    });
  }

  // validate parameters in filter purchase form
  let filterform = document.querySelector(".filter-form");
  if (filterform != null) {
    filterform.addEventListener("submit", (evnt) => {
      let costbelow = Number(
        document.querySelector("input[name='below']").value
      );
      let costabove = Number(
        document.querySelector("input[name='above']").value
      );

      if (isNaN(costbelow) || isNaN(costabove)) {
        alert("Cost of purchases should be numeric values. Try again!");
        evnt.preventDefault();
      } else if (
        !(isNaN(costbelow) || isNaN(costabove)) &&
        costbelow != 0 &&
        costabove != 0 &&
        costbelow < costabove
      ) {
        alert(
          "'Cost Below' field should be greater than the 'Cost Above' field. Try again!"
        );
        evnt.preventDefault();
      } else if (
        !(isNaN(costbelow) || isNaN(costabove)) &&
        (costbelow < 0 || costabove < 0)
      ) {
        alert("Prices can't be of negative values. Try again!");
        evnt.preventDefault();
      }

      // NB: empty date is set by default to current date in php
    });
  }
};

// This function prepares the login display
function loginSetup() {
  let toploginbtn = document.querySelector(".switch .login-btn");
  toploginbtn.classList.add("active");
  toploginbtn.classList.add("btn-success");
  let topsignupbtn = document.querySelector(".switch .signup-btn");

  topsignupbtn.classList.remove("active");
  topsignupbtn.classList.remove("btn-success");

  let loginform = document.querySelector(".login-form");
  loginform.classList.remove("d-none");

  let signupform = document.querySelector(".signup-form");
  signupform.classList.add("d-none");
}

// This function prepares the sign up display
function signupSetup() {
  let toploginbtn = document.querySelector(".switch .login-btn");
  toploginbtn.classList.remove("active");
  toploginbtn.classList.remove("btn-success");

  let topsignupbtn = document.querySelector(".switch .signup-btn");
  topsignupbtn.classList.add("active");
  topsignupbtn.classList.add("btn-success");

  let loginform = document.querySelector(".login-form");
  loginform.classList.add("d-none");

  let signupform = document.querySelector(".signup-form");
  signupform.classList.remove("d-none");
}

// email validation using regex
function ValidateEmail(mail) {
  if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)) return true;
  return false;
}
