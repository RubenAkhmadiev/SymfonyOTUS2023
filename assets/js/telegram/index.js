//Initialization constants
const removeBasketClassName = "remove-basket";
const apiUrl = "http://127.0.0.1:8015";
// const telegram = window.Telegram.WebApp;

// console.log(telegram.initDataUnsafe.user.id);

document.addEventListener('DOMContentLoaded', function() {
  getProducts();
  pay();
  finishOrder();
});

let countProductsValue = 0,
  clickProducts = function() {
    let productButtons = document.querySelectorAll(".menu .item-menu button");

    productButtons.forEach(item => {
      item.addEventListener("click", function() {

        if (item.classList.contains(removeBasketClassName)) {
          removeProduct(item);
        } else {
          addProduct(item);
        }

        item.classList.toggle(removeBasketClassName);

        if (countProductsValue > 10) {
          alert("Sorry, we can go you only ten products!");
          countProductsValue = 0;
        }
        if (countProductsValue < 0) {
          alert("Продуктов не может быть меньше 0!");
          countProductsValue = 0;
        }

        let countProductsValueElement = document.getElementById("countProductsValue");
        countProductsValueElement.innerHTML = countProductsValue;
      });
    });
  };

let addProduct = function(item) {
    item.innerHTML = "Убрать";
    countProductsValue += 1;
  },
  removeProduct = function(item) {
    item.innerHTML = "Добавить";
    countProductsValue -= 1;
  };

let getProducts = async function() {
  let response = await fetch(apiUrl + "/telegram/products");

  if (response.ok) {
    let products = await response.json();
    console.log(products);
    products.forEach((item, index) => {
      if (index < 6) {
        showProduct(item);
      }
    });

    clickProducts();
  } else {
    console.log("Не удалось получить данные с сервера, код ошибки: " + response.status);
  }
};

let showProduct = function(item) {
  // console.log(item.title);

  let itemMenuDiv = document.createElement("div");
  itemMenuDiv.classList = "item-menu";

  let itemMenuTitleDiv = document.createElement("div");
  itemMenuTitleDiv.classList = "item-menu-title";
  // itemMenuTitleDiv.innerHTML = item.title.slice(0, 5) + telegram.initDataUnsafe.user.id;
  itemMenuTitleDiv.innerHTML = item.name.slice(0, 5) + ":" + item.price;

  let itemMenuImg = document.createElement("img");
  itemMenuImg.src = "assets/images/imageFood.jpg";

  let itemMenuPriceDiv = document.createElement("div");
  itemMenuPriceDiv.classList = "item-menu-price";

  let addBasketButton = document.createElement("button");
  addBasketButton.classList = "add-basket";
  addBasketButton.innerHTML = "Добавить";

  itemMenuDiv.append(itemMenuTitleDiv, itemMenuImg, itemMenuPriceDiv, addBasketButton);
  document.getElementById("menu").append(itemMenuDiv);
}

let pay = function() {
  let payButton = document.getElementById("pay");
  payButton.addEventListener("click", function() {
    let containerMenu = document.querySelectorAll(".container-menu"),
      containerPay = document.querySelectorAll(".container-pay");

    containerMenu[0].classList = "display-none";
    containerPay[0].classList.toggle("display-none");
  });
}

let finishOrder = function() {
  let finishOrder = document.getElementById("formPay");
  finishOrder.addEventListener("submit", function(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    console.log(formData.get("name"));
    const formDataObject = {
      name: formData.get("name"),
      phone: formData.get("phone"),
      email: formData.get("email"),
      address: formData.get("address")
    };

    let formDataRequest = new FormData();
    formDataRequest.append('name', formData.get("name"));
    formDataRequest.append('phone', formData.get("phone"));
    formDataRequest.append('email', formData.get("email"));
    formDataRequest.append('address', formData.get("address"));

    console.log(formDataRequest);

    fetch(apiUrl + "/telegram/pay",
      {
        body: formDataRequest,
        method: "post"
      });

    // console.log(request.json());
    request.json().then(function() {
      alert("Успешно оформлен")
    });
  });
}
